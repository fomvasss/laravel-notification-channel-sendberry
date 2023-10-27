# Sendberry notifications channel for Laravel 5.5+

Here's the latest documentation on Laravel's Notifications System: 

https://laravel.com/docs/master/notifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fomvasss/laravel-notification-channel-senberry.svg?style=flat-square)](https://packagist.org/packages/fomvasss/laravel-notification-channel-senberry)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-notification-channel-sendberry.svg?style=flat-square)](https://scrutinizer-ci.com/g/fomvasss/laravel-notification-channel-sendberry)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/fomvasss/laravel-notification-channel-sendberry/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/fomvasss/laravel-notification-channel-sendberry/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-notification-channel-senberry.svg?style=flat-square)](https://packagist.org/packages/fomvasss/laravel-notification-channel-senberry)

This package makes it easy to send notifications using [sendberry.com](https://www.sendberry.com/) with Laravel 9.0+.

## Contents

- [Installation](#installation)
    - [Setting up](#setting-up)
- [Usage](#usage)
    - [Available Message methods](#available-methods)
- [Changelog](#changelog)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

```bash
composer require fomvasss/laravel-notification-channel-senberry
```

The service provider gets loaded automatically. Or you can do this manually:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Sendberry\SendberryServiceProvider::class,
],
```

### Setting up 

Add your Sendberry token, default sender name (or phone number), test mode to your `config/services.php`:

```php
// config/services.php
...
'sendberry' => [
    'username'  => env('SENDBERRY_USERNAME'),
    'password'  => env('SENDBERRY_PASSWORD'),
    'auth_key'  => env('SENDBERRY_AUTH_KEY'),
    'from'  => env('SENDBERRY_FROM'),
    'webhook'  => env('SENDBERRY_WEBHOOK'),
    'test_mode'  => env('SENDBERRY_TEST_MODE', false),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Sendberry\SendberryMessage;
use NotificationChannels\Sendberry\SendberryChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SendberryChannel::class];
    }

    public function toTurboSms($notifiable)
    {
        return (new SendberryMessage())->content("Hello SMS!!!")->test(true);
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForSendberry()` method, which returns a phone number
or an array of phone numbers.

```php
public function routeNotificationForSendberry()
{
    return $this->phone;
}
```

### Available methods

`from()`: Sets the sender's name or phone number.

`content()`: Set a content of the notification message.

`date()`: Example argument = `12.05.2020`

`time()`: Example argument = `13:00`

`test()`: Test SMS sending

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email fomvasss@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [fomvasss](https://github.com/fomvasss)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
