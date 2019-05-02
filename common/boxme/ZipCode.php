<?php


namespace common\boxme;


use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class ZipCode extends BaseObject
{

    public $province;
    public $district;

    private $_zipCode;

    private $_data;
    public function init()
    {
        parent::init();
        $this->getData();
    }

    public function getData()
    {
        if ($this->_data === null) {
            $this->_data = require Yii::getAlias('@common/models/boxme/idZipCode.php');
        }
        return $this->_data;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function getZipCode()
    {
        if ($this->_zipCode === null || (is_array($this->_zipCode) && count($this->_zipCode) === 0)) {
            $this->_zipCode = [];
            $province = ArrayHelper::getValue($this->getData(),$this->province,[]);
            if(count($data = isset($province[$this->district]) ? $province[$this->district] : []) > 0 && isset($data[0]) && count($data = $data[0]) > 0){
                $this->_zipCode = ArrayHelper::getColumn($data,'zip',false);
                $this->_zipCode = array_filter($this->_zipCode, function ($item){
                    return (int)$item !== 0;
                });
            }


        }
        return $this->_zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->_zipCode = $zipCode;
    }
}