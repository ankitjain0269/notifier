<?php
namespace App\Core\Notification\Drivers;

use App\Core\Notification\Response;
use App\Core\Notification\Token;
use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Apns
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
class ApnsDriver implements DriverContract
{
    /**
     * @var
     */
    private $bundleIdentifier;
    /**
     * @var
     */
    private $certificate;
    /**
     * @var
     */
    private $url;

    /**
     * @var \App\Core\Notification\Response
     */
    private $response;
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var array
     */
    private static $inactiveReason = ['BadDeviceToken', 'Unregistered'];

    /**
     * @var \App\Core\Notification\Drivers\Message
     */
    private $message;

    /**
     * Apns constructor.
     *
     * @param $bundleIdentifier
     * @param $certificate
     * @param $url
     */
    public function __construct($bundleIdentifier, $certificate, $url)
    {
        $this->bundleIdentifier = $bundleIdentifier;
        $this->certificate = $certificate;
        $this->url = $url;
        $this->response = new Response();
        $this->client = new Client();
        $this->message=new Message();

    }

    /**
     * @param $tokens
     *
     * @param \Closure $callback
     *
     * @return \App\Core\Notification\Response *
     * @author   Ankit
     */
    public function send($tokens,Closure $callback)
    {
        call_user_func($callback, $this->message);
        if (!is_array($tokens))
            $tokens = [$tokens];
        array_walk($tokens,[$this,'dispatch']);
        return $this->response;
    }

    /**
     * @param $token
     *
     * @throws \Exception
     * @author Ankit
     */
    private function dispatch($token)
    {
        $data=['aps' => ['alert' => ['title'=>$this->message->getTitle(),'body'=>$this->message->getBody()]]];
        if(!empty($this->message->getAction())) {
            $data['userAction'] = $this->message->getAction();
        }
        try {
            $result = $this->client->post("{$this->url}/3/device/{$token}", [
                'headers' => ["apns-topic" => $this->bundleIdentifier],
                'json' => $data,
                'cert' => [realpath($this->certificate), ''],
                'version' => 2
            ]);
        } catch (RequestException $e) {
            $result = $e->getResponse();
        }
        $this->mapToResponse($result, $token);
    }

    /**
     * @param $result
     * @param $token
     *
     * @author Ankit
     */
    private function mapToResponse($result, $token)
    {
        if (empty($result))
            $this->response->setToken((new Token())->setStatus(Token::FAILED)->setToken($token)->setReason('No Result'))->incrementFailed();
        elseif ($result->getStatusCode() == 200)
            $this->response->setToken((new Token())->setStatus(Token::SENT)->setToken($token))->incrementSuccess();
        elseif (in_array(json_decode($result->getBody()->getContents(), true)['reason'], self::$inactiveReason))
            $this->response->setToken((new Token())->setStatus(Token::INACTIVE)->setToken($token)->setReason(json_decode($result->getBody()->getContents(), true)['reason']))->incrementFailed();
        elseif ($result->getStatusCode() == 429)
            $this->response->setToken((new Token())->setStatus(Token::SENT)->setToken($token)->setReason(json_decode($result->getBody()->getContents(), true)['reason']))->incrementSuccess();
        else
            $this->response->setToken((new Token())->setStatus(Token::FAILED)->setToken($token)->setReason(json_decode($result->getBody()->getContents(), true)['reason']))->incrementFailed();
    }

}