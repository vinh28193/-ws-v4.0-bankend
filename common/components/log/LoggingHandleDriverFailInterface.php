<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-02
 * Time: 15:23
 */

namespace common\components\log;

interface LoggingHandleDriverFailInterface
{

    /**
     * @param $driver LoggingDriverInterface
     * @return $this
     */
    public function resolve($driver);
}