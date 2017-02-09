<?php

namespace App\Core\Notification;


/**
 * Class Response
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
/**
 * Class Response
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
class Response
{
    /**
     * @var array
     */
    public $tokens=[];

    /**
     * @var int
     */
    public $success=0;

    /**
     * @var int
     */
    public $failed=0;

    /**
     * @var
     */
    public $raw;

    /**
     * @param \App\Core\Notification\Token $token
     *
     * @return $this
     * @author Ankit
     */
    public function setToken(Token $token)
    {
        $this->tokens[] = $token;
        return $this;
    }

    /**
     * @param array $raw
     *
     * @return $this
     * @author Ankit
     */
    public function setRaw(array $raw)
    {
        $this->raw=$raw;
        return $this;
    }


    /**
     * @return $this
     * @author Ankit
     */
    public function incrementSuccess()
    {
        $this->success ++;
        return $this;
    }


    /**
     * @return $this
     * @author Ankit
     */
    public function incrementFailed()
    {
        $this->failed ++;
        return $this;
    }


    /**
     * @param $success
     *
     * @return $this
     * @author Ankit
     */
    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }


    /**
     * @param $failed
     *
     * @return $this
     * @author Ankit
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;
        return $this;
    }

}