<?php

namespace NotificationChannels\Sendberry;

class SendberryMessage
{
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from = '';
    /**
     * The message content.
     *
     * @var string
     */
    public $content = '';

    /**
     * Time of sending a message.
     *
     * @var string
     */
    public $time;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $webhook = '';

    /**
     * @var bool
     */
    public $test;

    /**
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Create a new message instance.
     *
     * @param  string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number or sender name the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string|null $time
     * @return $this
     */
    public function time(string $time = null)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param string|null $time
     * @return $this
     */
    public function date(string $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function webhook(string $url = null)
    {
        $this->webhook = $url;

        return $this;
    }

    /**
     * Set the test SMS - imitation sending.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function test(bool $test = false)
    {
        $this->test = $test;

        return $this;
    }
}
