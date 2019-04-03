<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:06
 */

namespace common\components\log;

/**
 * Interface LoggingDriverInterface
 * @package common\components\log
 * @property $provided string
 */
interface LoggingDriverInterface
{

    /**
     * @return string
     */
    public function getProvided();

    /**
     * @param $action string
     * @param $message string
     * @param array $params array
     * @return mixed|boolean
     */
    public function pushData($action, $message, $params = []);

    /**
     * @param $condition string|array
     * @return mixed
     */
    public function pullData($condition);
}