<?php

namespace Marlosoft\Semaphore;

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
    private const MAX = 1000;
    private const MIN = 100;
    private const PAGE = 1;

    /** @var string $apiKey */
    private $apiKey;

    /** @var string $senderName */
    private $senderName;

    /** @var  */
    private $httpClient;

    /**
     * @param Response $response
     * @param string|null $className
     * @param bool $multiple
     * @return mixed
     */
    private function parse(Response $response, ?string $className = null, bool $multiple = false)
    {
        $data = (array)json_decode((string)$response->getBody(), true);

        if ($className) {
            if ($multiple) {
                $obj = [];
                foreach ($data as $v) {
                    $obj[] = new $className($v);
                }

                return $obj;
            }

            return new $className($data);
        }

        return $data;
    }

    /**
     * @param int|null $limit
     * @throws Exception
     */
    private function validateLimit(?int $limit)
    {
        if ($limit && $limit > 1000) {
            throw new Exception('Result limit exceeded.');
        }
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $options
     * @param string|null $className
     * @param bool $multiple
     * @return mixed
     * @throws Exception
     */
    public function execute(
        string $method,
        string $path,
        array $options,
        ?string $className = null,
        bool $multiple = false
    ) {
        try {
            $response = $this->httpClient->request(strtoupper($method), $path, $options);
            return $this->parse($response, $className, $multiple);
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
     * @param string|array $recipient
     * @param string $message
     * @param bool $priority
     * @param Sender|string|null $sender
     * @return Message[]
     * @throws Exception
     */
    public function sendMessage($recipient, string $message, $priority = false, $sender = null): array
    {
        $recipient = (array)$recipient;
        if (count($recipient) > self::MAX) {
            throw new Exception('Bulk sending only allows up to ' . self::MAX . ' numbers per API call.');
        }

        return $this->execute('post', $priority ? 'priority' : 'messages', [
            'form_params' => [
                'sendername' => $sender ?: $this->senderName,
                'apikey' => $this->apiKey,
                'message' => $message,
                'number' => $recipient,
            ],
        ], Message::class, true);
    }

    /**
     * @param string $recipient
     * @param string $message
     * @param Sender|string|null $sender
     * @return Message|null
     * @throws Exception
     */
    public function sendOTP(string $recipient, string $message, $sender = null): ?Message
    {
        return $this->execute('post', 'otp', [
            'form_params' => [
                'sendername' => $sender ?: $this->senderName,
                'apikey' => $this->apiKey,
                'message' => $message,
                'number' => $recipient,
            ],
        ], Message::class);
    }

    /**
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function getMessages($options = []): array
    {
        $options['apikey'] = $this->apiKey;

        return $this->execute('get', 'messages', $options, Message::class);
    }

    /**
     * @param int $messageId
     * @return Message|null
     * @throws Exception
     */
    public function getMessage(int $messageId): ?Message
    {
        return $this->execute(
            'get',
            'messages/' . $messageId,
            ['query' => ['apikey' => $this->apiKey]],
            Message::class
        );
    }

    /**
     * @return Account|null
     * @throws Exception
     */
    public function getAccount(): ?Account
    {
        return $this->execute('get', 'account', ['query' => ['apikey' => $this->apiKey]], Account::class);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Sender[]
     * @throws Exception
     */
    public function getSenders($limit = self::MAX, $page = self::PAGE): array
    {
        $this->validateLimit($limit);

        return $this->execute('get', 'account/sendernames', [
            'query' => [
                'apikey' => $this->apiKey,
                'limit' => $limit,
                'page' => $page,
            ]
        ], Sender::class, true);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return User[]
     * @throws Exception
     */
    public function getUsers($limit = self::MAX, $page = self::PAGE): array
    {
        $this->validateLimit($limit);

        return $this->execute('get', 'account/users', [
            'query' => [
                'apikey' => $this->apiKey,
                'limit' => $limit,
                'page' => $page,
            ]
        ], User::class, true);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return Transaction[]
     * @throws Exception
     */
    public function getTransactions($limit = self::MAX, $page = self::PAGE): array
    {
        $this->validateLimit($limit);

        return $this->execute('get', 'account/transactions', [
            'query' => [
                'apikey' => $this->apiKey,
                'limit' => $limit,
                'page' => $page,
            ]
        ], Transaction::class, true);
    }
}
