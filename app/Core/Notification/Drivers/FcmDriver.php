<?php
namespace App\Core\Notification\Drivers;
use App\Core\Notification\Response;
use App\Core\Notification\Token;
use Closure;
use GuzzleHttp\Client;

/**
 * Class Fcm
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
class FcmDriver implements DriverContract
{
    /**
     * @var
     */
    private $key;
    /**
     * @var
     */
    private $url;
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var \App\Core\Notification\Response
     */
    private $response;
    /**
     * @var \App\Core\Notification\Drivers\Message
     */
    private $message;

    /**
     * Fcm constructor.
     *
     * @param $key
     * @param $url
     */
    public function __construct($key, $url)
    {
        $this->key = $key;
        $this->url = $url;
        $this->client = new Client();
        $this->response = new Response();
        $this->message=new Message();
    }

    /**
     * @param $tokens
     * @param \Closure $callback
     *
     * @return \App\Core\Notification\Response
     * @author Ankit
     */
    public function send($tokens, Closure $callback)
    {
        call_user_func($callback, $this->message);
        if (!is_array($tokens))
             $tokens = [$tokens];
        $data=$this->processData();
        $response = $this->client->post($this->url, [
            'headers' => [
                'Authorization' => "key={$this->key}"],
            'json' => ['data' => $data, 'registration_ids' => $tokens],
        ]);
        $this->mapToResponse($response, $tokens);
        return $this->response;
    }

    /**
     * @param $response
     * @param $tokens
     *
     * @author Ankit
     */
    private function mapToResponse($response, $tokens)
    {
        $body=$response->getBody()->getContents();
        $response=json_decode($body, true);
        $this->setTokens($response, $tokens);
        $this->response->setRaw($response);
        $this->response->setFailed($response['failure'])->setSuccess($response['success']);
    }

    /**
     * @param $response
     * @param $tokens
     *
     * @author Ankit
     */
    private function setTokens($response, $tokens)
    {
        $tokens = array_combine($tokens, $response['results']);
        foreach ($tokens as $token => $result) {
            if (isset($result['message_id'])) {
                $token = (new Token())->setStatus(Token::SENT)->setToken($token);
                if (isset($result['registration_id']))
                    $token->setNewToken($result['registration_id']);
                $this->response->setToken($token);
            } elseif (isset($result['error'])) {
                if ($result['error'] == 'InvalidRegistration' || $result['error'] == 'NotRegistered')
                    $this->response->setToken((new Token())->setStatus(Token::INACTIVE)->setToken($token)->setReason($result['error']));
                else
                    $this->response->setToken((new Token())->setStatus(Token::FAILED)->setToken($token)->setReason($result['error']));
            }
        }
    }

    /**
     * @return array
     * @author Ankit
     */
    private function processData()
    {
        $data=['title'=>$this->message->getTitle(),'message'=>$this->message->getBody()];
        if(!empty($this->message->getAction()))
            $data['userAction']=$this->message->getAction();
        return $data;
    }
}