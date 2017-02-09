<?php
namespace App\Core\Notification;

use App\Core\Notification\Drivers\ApnsDriver;
use App\Core\Notification\Drivers\FcmDriver;
use InvalidArgumentException;

/**
 * Class Notification
 *
 * @package App\Core\Notification
 * @author  Ankit
 */
class Notification
{
    /**
     * @param null $name
     *
     * @return \App\Core\Notification\Drivers\DriverContract
     * @author Ankit
     */
    public function driver($name = null)
    {
        return $this->resolve($name);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @author Ankit
     */
    private function resolve($name)
    {
        $driverMethod = 'create' . ucfirst($name) . 'Driver';
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}(config("services.".$name));
        } else {
            throw new InvalidArgumentException("Driver [{$name}] is not supported.");
        }
    }

    /**
     * @param $config
     *
     * @author Ankit
     * @return \App\Core\Notification\Drivers\ApnsDriver
     */
    protected function createApnsDriver($config)
    {
        return (new ApnsDriver($config['bundle_identifier'],$config['certificate_path'],$config['url']));
    }

    /**
     * @param $config
     *
     * @author Ankit
     * @return \App\Core\Notification\Drivers\FcmDriver
     */
    protected function createFcmDriver($config)
    {
        return (new FcmDriver($config['key'],$config['url']));
    }

}