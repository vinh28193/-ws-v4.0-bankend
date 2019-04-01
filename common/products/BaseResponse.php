<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 09:35
 */

namespace common\products;


abstract class BaseResponse extends \yii\base\BaseObject
{

    /**
     * @var BaseGate
     */
    public $gate;


    public function __construct(BaseGate $gate, $config = [])
    {
        parent::__construct($config);
        $this->gate = $gate;
    }

    /**
     * @param $response
     * @return BaseProduct
     */
    abstract public function parser($response);

    /**
     * @param $value
     * @return bool
     */
    public function isEmpty($value)
    {
        return $this->gate->isEmpty($value);
    }

    /**
     * @param $id
     * @return bool
     */
    public function isBanned($id)
    {
        return $this->gate->isBanned($id);
    }
}