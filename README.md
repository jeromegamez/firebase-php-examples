# Firebase Admin SDK Examples

Examples for using https://github.com/kreait/firebase-php

This project is based on a default Symfony Flex application.

## How to use

```bash
git clone git@github.com:jeromegamez/firebase-php-examples.git
cd firebase-php-examples
cp .env.dist .env
# Set FIREBASE_CREDENTIALS_PATH with the path to your credentials file
bin/console list app
```

## Available commands

```
bin/console app:create-user       # Create a Firebase user
bin/console app:fcm:send-message  # Send an FCM message
bin/console app:reset-project     # Reset parts of a Firebase project to their initial state
```
