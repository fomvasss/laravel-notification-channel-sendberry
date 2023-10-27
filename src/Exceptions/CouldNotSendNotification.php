<?php

namespace NotificationChannels\Sendberry\Exceptions;

use NotificationChannels\Sendberry\SendberryMessage;
use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * @return static
     */
    public static function invalidFrom()
    {
        return new static('Notification was not sent.Invalid `from` number.');
    }

    public static function missingFrom()
    {
        return new static('Notification was not sent. Missing `from` number.');
    }

    /**
     * @return CouldNotSendNotification
     */
    public static function invalidReceiver()
    {
        return new static("The notifiable did not have a receiving phone number. Add a <routeNotificationForSmsru>
            method or a <phone> attribute to your notifiable.");
    }

    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be either `".SendberryMessage::class);
    }
}
