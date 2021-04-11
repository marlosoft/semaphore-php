<?php

namespace Marlosoft\Semaphore;

use Exception as BaseException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Throwable;

/**
 * Class Client
 * @package Marlosoft\Semaphore
 */
class Client
{
    private const API_ENDPOINT = 'https://api.semaphore.co/api/v4/';
    private const BULK_LIMIT = 1000;

    /** @var string $apiKey */
    private $apiKey;

    /** @var string $senderName */
    private $senderName;

    /** @var  */
    private $httpClient;

    /**
     * @param Response $response
     * @param string|null $className
     * @return array
     * @throws BaseException
     */
    private function parse(Response $response, ?string $className = null): array
    {
        $data = (array)json_decode((string)$response->getBody(), true);
        if ($className) {
            $obj = [];
            foreach ($data as $v) {
                $obj[] = new $className($v);
            }

            return $obj;
        }

        return $data;
    }

    /**
     * @param string $path
     * @param array $options
     * @param string|null $className
     * @return array
     * @throws Exception
     */
    public function execute(string $path, array $options, ?string $className = null): array
    {
        try {
            $response = $this->httpClient->get($path, $options);
            return $this->parse($response, $className);
        } catch (RequestException $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();

            if ($exception->hasResponse()) {
                $code = $exception->getResponse()->getStatusCode();
                $message = (string)$exception->getResponse()->getBody();
            }
        } catch (Throwable $throwable) {
            $code = $throwable->getCode();
            $message = $throwable->getMessage();
        }

        throw new Exception($message, $code);
    }

    /**
     * Client constructor.
     * @param string $apiKey
     * @param string|null $senderName
     * @throws InvalidArgumentException
     */
    public function __construct(string $apiKey, ?string $senderName = null)
    {
        $this->apiKey = $apiKey;
        $this->senderName = $senderName;
        $this->httpClient = new HttpClient([
            'base_uri' => self::API_ENDPOINT,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @param array|string $recipient
     * @param string $message
     * @param false $priority
     * @return Message[]
     * @throws Exception
     */
    public function sendMessage($recipient, string $message, $priority = false): array
    {
        $recipient = (array)$recipient;
        if (count($recipient) > self::BULK_LIMIT) {
            throw new Exception('Bulk sending only allows up to ' . self::BULK_LIMIT . ' numbers per API call.');
        }

        return $this->execute($priority ? 'priority' : 'messages', [
            'form_params' => [
                'sendername' => $this->senderName,
                'apikey' => $this->apiKey,
                'message' => $message,
                'number' => $recipient,
            ],
        ], Message::class);
    }

    /**
     * @param string $recipient
     * @param string $message
     * @return Message|null
     * @throws Exception
     */
    public function sendOTP(string $recipient, string $message): ?Message
    {
        $messages = $this->execute('otp', [
            'form_params' => [
                'sendername' => $this->senderName,
                'apikey' => $this->apiKey,
                'message' => $message,
                'number' => $recipient,
            ],
        ], Message::class);

        return $messages[0] ?? null;
    }

    /**
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function getMessages($options = []): array
    {
        $options['apikey'] = $this->apiKey;

        return $this->execute('messages', $options, Message::class);
    }

    /**
     * @param int $messageId
     * @return Message|null
     * @throws Exception
     */
    public function getMessage(int $messageId): ?Message
    {
        $messages = $this->execute(
            'messages/' . $messageId,
            ['query' => ['apikey' => $this->apiKey]],
            Message::class
        );

        return $messages[0] ?? null;
    }
}
