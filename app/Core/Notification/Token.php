<?php
namespace App\Core\Notification;

/**
 * Class Token
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
class Token
{
    /**
     *
     */
    const SENT='sent';
    /**
     *
     */
    const FAILED='failed';
    /**
     *
     */
    const INACTIVE='inactive';
    /**
     * @var
     */
    public $token;
    /**
     * @var
     */
    public $status;
    /**
     * @var null
     */
    public $newToken=null;
    /**
     * @var null
     */
    public $reason=null;

    /**
     * @var bool
     */
    private $newTokenExist=false;


    /**
     * @param $token
     *
     * @return $this
     * @author Ankit
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


    /**
     * @param $status
     *
     * @return $this
     * @author Ankit
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param $newToken
     *
     * @return $this
     * @author Ankit
     */
    public function setNewToken($newToken)
    {
        $this->newToken = $newToken;
        $this->newTokenExist = true;
        return $this;
    }

    /**
     * @param $reason
     *
     * @return $this
     * @author Ankit
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return bool
     * @author Ankit
     */
    public function hasNewToken()
    {
        return $this->newTokenExist;
    }
}