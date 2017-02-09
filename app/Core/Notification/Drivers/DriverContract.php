<?php
namespace App\Core\Notification\Drivers;
use Closure;


/**
 * Class Apns
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
interface DriverContract
{
    /**
     * @param $tokens
     *
     * @param \Closure $closureFunction
     *
     * @return \App\Core\Notification\Response *
     * @author   Ankit
     */
    public function send($tokens,Closure $closureFunction);
}