## Index
- [Introduction](#introduction)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)


### Introduction
This can be used as an alternative of pusher for message broadcasting.

This package is being used in our internal projects. It is still in beta testing. For this reason we did not publish this package in [packagist](https://packagist.org/).

This package is dependent on [laravel-notification-channels/fcm](https://github.com/laravel-notification-channels/fcm) and [kreait/laravel-firebase](https://github.com/kreait/laravel-firebase). So for details please checkout those documentation.

### Installation
To use this package in your project please follow the bellow instructions.

- Copy the bellow code to add this repository to your `composer.json`. 

        "repositories": [
            ....
            {
                "type": "vcs",
                "url": "https://github.com/virtunus/frdb-notification.git"
            }
        ],
        "require": {
            "virtunus/frdb-notification": "dev-master"
        }

    > Checkout to know more about [composer repository](https://getcomposer.org/doc/05-repositories.md#repository).

- Now update your composer by running `composer update` command at your project terminal.

### Configuration
In order to access a Firebase project and its related services using a server SDK, requests must be authenticated.
For server-to-server communication this is done with a Service Account.

The package uses auto discovery for the default project to find the credentials needed for authenticating requests to
the Firebase APIs by inspecting certain environment variables and looking into Google's well known path(s).

If you don't already have generated a Service Account, you can do so by following the instructions from the
official documentation pages at https://firebase.google.com/docs/admin/setup#initialize_the_sdk.

Once you have downloaded the Service Account JSON file, you can configure the package by specifying
environment variables starting with `FIREBASE_` in your `.env` file. Usually, the following are
required for the package to work:

```
# relative or full path to the Service Account JSON file
FIREBASE_CREDENTIALS=
# You can find the database URL for your project at
# https://console.firebase.google.com/project/_/database
FIREBASE_DATABASE_URL=https://<your-project>.firebaseio.com
```

For further configuration, please see [config/firebase.php](config/firebase.php). You can modify the configuration
by copying it to your local `config` directory or by defining the environment variables used in the config file:

```bash
# Laravel
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config

# Lumen
mkdir -p config
cp vendor/kreait/laravel-firebase/config/firebase.php config/firebase.php
```

> This configuration is copied from https://github.com/kreait/laravel-firebase#configuration

### Usage
Just return `FrdbChannel::class` from `via` method in your notification class.

The sample notification class:
    
    <?php

    namespace App\Notifications;

    use Illuminate\Notifications\Notification;
    use Virtunus\FrdbNotification\FrdbChannel;

    class SampleNotification extends Notification
    {
        /**
        * Get the notification's delivery channels.
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function via($notifiable)
        {
            return ['database', 'mail', FrdbChannel::class];
        }

        /**
        * Get the array representation of the notification.
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function toArray($notifiable)
        {
            return [
                'message' => 'toArray method was called',
            ];
        }
    }

By default the reference will be the **namespace** of the notification class, In this case `/App/Notifications/SampleNotification/{notifiable-id}`.

You can customize the default behaviour as follows


    <?php

    namespace App\Notifications;

    use Illuminate\Notifications\Notification;
    use Virtunus\FrdbNotification\FrdbChannel;

    class SampleNotification extends Notification
    {
        /**
        * Get the notification's delivery channels.
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function via($notifiable)
        {
            return [FrdbChannel::class];
        }

        /**
        * Get the array representation of the notification.
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function toArray($notifiable)
        {
            return [
                'message' => 'toArray method was called',
            ];
        }

        /**
        * Get the method to store data in firebase realtime db
        * 
        * @param  mixed  $notifiable
        * @return array
        */
        public function frdbStoreMethod(): string
        {
            return 'push';
        }

        /**
        * Get the firebase realtime database representation of the notification.
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function toFrdb($notifiable)
        {
            return [
                'message' => 'toFrdb method was called',
            ];
        }

        /**
        * Get the reference
        *
        * @param  mixed  $notifiable
        * @return array
        */
        public function frdbReference($notifiable): string
        {
            return '/firebase-database-ref';
        }
    }


The default store method this `set`. The supported store methods are `set`, `push`, `delete` etc. To know more details checkout [saving data](https://firebase-php.readthedocs.io/en/stable/realtime-database.html#saving-data).
