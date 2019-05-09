# Firebase Admin SDK Examples 

Example Symfony Project using https://github.com/kreait/firebase-php 

[![Discord](https://img.shields.io/discord/523866370778333184.svg?color=7289da&logo=discord)](https://discord.gg/nbgVfty)

## How to use

```bash
git clone git@github.com:jeromegamez/firebase-php-examples.git
cd firebase-php-examples
composer install
cp .env.dist .env
# Set FIREBASE_CREDENTIALS_PATH with the path to your credentials file
bin/console list app
```

## Running the server

```bash
# Show available routes
bin/console debug:router
# Start the server
bin/console server:start

```

## Available commands

```
bin/console app:create-user                  # Creates a Firebase user
bin/console app:fcm:send-message             # Send an FCM message
bin/console app:remote-config:list-versions  # Lists all remote config versions
bin/console app:reset-project                # Reset parts of a Firebase project to its initial state
```

## Deploying to Google Cloud Engine

You can deploy the application to Google Cloud Engine with the following command:

```bash
gcloud app deploy
```

The Firebase PHP SDK will autodiscover the credentials on the GCE environment to connect to the
Firebase services. If you run into authorization issues, please consult 
https://firebase-php.readthedocs.io/en/latest/troubleshooting.html#forbidden-errors
