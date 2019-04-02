<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:06
 */

namespace common\components\log;

interface LoggingDriverInterface
{

    /**
     * LoggingDriverInterface constructor.
     * @param Logging $logging
     * @param array $config
     */
//    public function __construct(Logging $logging, $config);

    /**
     * @param $action string
     * @param $message string
     * @param array $params array
     * @return mixed|boolean
     */
    public function push($action, $message, $params = []);

    /**
     * @param $condition string|array
     * @return mixed
     */
    public function pull($condition);
}