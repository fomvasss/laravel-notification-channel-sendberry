<?php

namespace NotificationChannels\Sendberry;

use GuzzleHttp\Client as HttpClient;
use NotificationChannels\Sendberry\Exceptions\CouldNotSendNotification;
use NotificationChannels\Sendberry\Exceptions\TransportException;
use Exception;

class SendberryApi
{
    /** @var HttpClient */
    protected $client;
    protected $authKey;
    protected $username;
    protected $password;
    protected $from;
    protected $webhook;
    protected $testMode;

    protected $baseUri = 'https://api.sendberry.com/';

    public function __construct(
        string $authKey,
        string $username,
        string $password,
        $from,
        $webhook,
        $testMode
    ) {
        $this->authKey = $authKey;
        $this->username = $username;
        $this->password = $password;
        $this->from = $from;
        $this->webhook = $webhook;
        $this->testMode = $testMode;

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @param $recipient
     * @param SendberryMessage $message
     * @return array
     */
    public function sendMessage($recipient, SendberryMessage $message)
    {
        if (!preg_match('/^[+]+[1-9][0-9]{9,14}$/', $this->from)) {
            if ($this->from === '') {
                throw CouldNotSendNotification::missingFrom();
            }

            if (!preg_match('/^[a-zA-Z0-9 ]+$/', $this->from)) {
                throw CouldNotSendNotification::invalidFrom();
            }
        }

        $body = [
            'key' => $this->authKey,
            'name' => $this->username,
            'password' => $this->password,
            'content' => $message->content,
            'from' => $this->from,
            'to' => [$recipient],
            'response' => 'JSON',
        ];

        if ($message->time) {
            $body['time'] = $message->time;
        }

        if ($message->date) {
            $body['date'] = $message->date;
        }

        if ($webhook = $message->webhook || $this->webhook) {
            $body['webhook'] = $webhook;
        }

        if (!is_null($message->test)) {
            $this->testMode = $message->test;
        }

        $url = $this->baseUri . 'SMS/SEND';

        return $this->getResponse($url, $body);
    }

    /**
     * @param string $url
     * @param array $body
     * @return array
     */
    public function getResponse($url, $body)
    {
        if ($this->testMode) {
            return [
                'url' => $url,
                'body' => $body,
                'info' => 'sendberry.test_mode.ok',
            ];
        }

        $response = $this->client->request('POST', $url, [
            'json' => $body
        ]);

        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportException $e) {
            throw new TransportException('Could not reach the remote Sendberry server.', $response, 0, $e);
        }

        if ($statusCode !== 200) {
            throw new TransportException('Unable to send the SMS.', $response);
        }

        $responseArr = json_decode($response->getBody()->getContents(), true);

        if (isset($responseArr['status']) && $responseArr['status'] !== 'ok') {
            throw new TransportException(sprintf("Unable to send the SMS. \n%s\n.", implode("\n", $responseArr['message'])));
        }

        return $responseArr;
    }
}
