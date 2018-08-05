# Firebase Admin SDK Examples 

Examples for using https://github.com/kreait/firebase-php

**Disclaimer:** This example application is just a Proof of Concept and doesn't 
actively follow best practices for neither Symfony nor Firebase 😅

<a href='https://ko-fi.com/jeromegamez' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://az743702.vo.msecnd.net/cdn/kofi2.png?v=0' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>

## How to use

```bash
git clone git@github.com:jeromegamez/firebase-php-examples.git
cd firebase-php-examples
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
bin/console app:create-user       # Create a Firebase user
bin/console app:fcm:send-message  # Send an FCM message
bin/console app:reset-project     # Reset parts of a Firebase project to their initial state
```

## Deploying to Google Cloud Engine

You can deploy the application to Google Cloud Engine with the following command:

```bash
gcloud app deploy
```

The Firebase PHP SDK will autodiscover the credentials on the GCE environment to connect to the
Firebase services. If you run into authorization issues, please consult 
https://firebase-php.readthedocs.io/en/latest/troubleshooting.html#forbidden-errors
