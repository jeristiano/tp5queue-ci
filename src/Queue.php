<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think;

use think\queue\Connector;

/**
 * Class Queue
 * @package think\queue
 * @method static push($job, $data = '', $queue = null)
 * @method static later($delay, $job, $data = '', $queue = null)
 * @method static pop($queue = null)
 * @method static marshal()
 */
class Queue
{
    /** @var Connector */
    protected static $connector;

    /**
     * @return \think\queue\Connector
     */
    private static function buildConnector ()
    {
        $target = __DIR__.'/../../../../'. 'config' . DIRECTORY_SEPARATOR . 'queue' . '.php';

        if (is_file($target)) {
            $options = include $target;
        } else {
            $config_local = './config.php';
            $options = include $config_local;
        }

        $type = !empty($options['connector']) ? $options['connector'] : 'Redis';

        if (!isset(self::$connector)) {

            $class = false !== strpos($type, '\\') ? $type : '\\think\\queue\\connector\\' . $type;

            self::$connector = new $class($options);
        }
        return self::$connector;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic ($name, $arguments)
    {
        return call_user_func_array([self::buildConnector(), $name], $arguments);
    }


}
