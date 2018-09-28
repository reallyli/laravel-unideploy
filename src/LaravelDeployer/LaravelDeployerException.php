<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/9/28
 * Time: 下午12:20
 */

namespace Reallyli\LaravelDeployer;

/**
 * 自定义一个异常处理类
 */
class LaravelDeployerException extends \Exception
{
    protected $message = '[Laravel-Deployer-Exception]';

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        $message = $this->message . $message;

        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": $this->message[{$this->code}]: {$this->message}\n";
    }
}
