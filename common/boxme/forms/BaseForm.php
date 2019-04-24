<?php


namespace common\boxme\forms;

use common\boxme\BoxmeClientCollection;
use yii\base\Model;

class BaseForm extends Model
{

    private $_boxme;

    public function getBoxme()
    {
        if ($this->_boxme) {
            $this->_boxme = new BoxmeClientCollection();
        }
        return $this->_boxme;
    }
}