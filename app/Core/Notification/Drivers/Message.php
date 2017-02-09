<?php

namespace App\Core\Notification\Drivers;

/**
 * Class Message
 *
 * @package App\Core\Notification\Drivers
 * @author  Ankit
 */
class Message
{

    /**
     * @var
     */
    private $body='';

    /**
     * @var
     */
    private $title='';

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * @param $body
     *
     * @return $this
     * @author Ankit
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param $title
     *
     * @return $this
     * @author Ankit
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}