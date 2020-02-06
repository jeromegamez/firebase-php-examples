<?php

namespace App\Command;

use DateTimeImmutable;
use Kreait\Firebase\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class UploadFileCommand extends Command
{
    protected static $defaultName = 'app:upload-file';

    /** @var Storage */
    private $storage;

    /** @var Filesystem */
    private $filesystem;

    protected function configure()
    {
        $this
            ->setDescription('Upload a file to Firebase storage')
            ->addArgument('source', InputArgument::REQUIRED, 'Local path to file')
            ->addArgument('target', InputArgument::REQUIRED, 'Remote path')
            ->addOption('bucket', null, InputOption::VALUE_OPTIONAL, 'If omitted, the default bucket will be used.')
            ->addOption('public', null, InputOption::VALUE_NONE, 'Whether the file should be publicly available')
        ;
    }

    public function __construct(Storage $storage, Filesystem $filesystem)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->filesystem = $filesystem;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $source = $input->getArgument('source');
        $target = $input->getArgument('target');
        $bucketName = $input->hasOption('bucket') ? $input->getOption('bucket') : null;
        $isPublic = (bool) $input->getOption('public');

        $bucket = $this->storage->getBucket($bucketName);

        $uploadOptions = array_filter([
            'name' => $target,
            'predefinedAcl' => $isPublic ? 'publicRead' : null,
        ]);

        $uploadedFile = $bucket->upload(fopen($source, 'rb'), $uploadOptions);

        $io->success('File uploaded');

        $io->title('GCS URI');
        $io->writeln($uploadedFile->gcsUri());

        $io->title('Signed URL (valid for 1h)');
        $io->writeln($uploadedFile->signedUrl((new DateTimeImmutable())->modify('+1 hour')));

        if ($isPublic) {
            $io->title('Public URL');
            $io->writeln("https://{$bucket->name()}.storage.googleapis.com/{$uploadedFile->name()}");
        }

        return 0;
    }
}
