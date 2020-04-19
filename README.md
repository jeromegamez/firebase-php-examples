# Firebase Admin SDK Examples 

Example Symfony Project using https://github.com/kreait/firebase-php 

[![Discord](https://img.shields.io/discord/523866370778333184.svg?color=7289da&logo=discord)](https://discord.gg/nbgVfty)
[![Sponsor](https://img.shields.io/static/v1?logo=GitHub&label=Sponsor&message=%E2%9D%A4&color=ff69b4)](https://github.com/sponsors/jeromegamez)

## How to use

```bash
git clone git@github.com:jeromegamez/firebase-php-examples.git
cd firebase-php-examples
composer install
cp .env.dist .env
# Set FIREBASE_CREDENTIALS with the path to your credentials file
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
bin/console app:upload-file                  # Upload a file to Firebase storage
```

## Deploying to Google Cloud Engine

You can deploy the application to Google Cloud Engine with the following command:

```bash
gcloud app deploy
```

Make sure to remove/override the credentials setting when running on GCE - the SDK SDK will 
autodiscover the credentials on the GCE environment to connect to the Firebase services.
