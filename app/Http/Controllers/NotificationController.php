<?php

namespace App\Http\Controllers;


use App\Core\Notification\Notification;
use App\Core\Notification\Response;
use Illuminate\Http\Request;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers
 * @author  Ankit
 */
class NotificationController extends Controller
{

    /**
     * @param $driver
     * @param \Illuminate\Http\Request $request
     * @param \App\Core\Notification\Notification $notification
     *
     * @return \App\Core\Notification\Response
     * @author Ankit
     */
    public function sendNotification($driver, Request $request, Notification $notification)
    {
        $this->validateFunction($request);
        $response = $notification->driver($driver)->send($request->tokens, function($message)use($request){
            $message->setBody($request->body)->setTitle($request->title);
        });
        $data=$this->createResponse($response);
        return response($data,200);
    }

    /**
     * @param $request
     *
     * @author Ankit
     */
    private function validateFunction($request)
    {
        $this->validate($request, [
            'body' => 'required',
            'title' => 'required',
            'tokens'=> 'required'
        ]);
    }

    /**
     * @param \App\Core\Notification\Response $response
     *
     * @return array
     * @author Ankit
     */
    private function createResponse(Response $response)
    {
        $data=[];
        foreach($response['tokens'] as $token){
            $data[]=[
                'token'=>$token->token,
                'status'=>$token->status,
                'new_token'=>$token->newToken,
            ];
        }
        return $data;
    }


}