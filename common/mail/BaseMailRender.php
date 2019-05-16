<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-02
 * Time: 15:20
 */

namespace common\mail;

use Yii;
use yii\base\BaseObject;
use yii\base\ViewContextInterface;
use yii\helpers\ArrayHelper;

abstract class BaseMailRender extends BaseObject implements MailRendererContextInterface, ViewContextInterface
{

    private $_params = [];
    public function getParams(){
        return $this->_params;
    }
    public function addParams($name,$value){
        $params = $this->getParams();
        $this->_params = ArrayHelper::merge($params,[$name => $value]);
    }

    /**
     * @param Template $template
     * @return mixed|void
     */
    public function extractTemplate($template)
    {
        $this->addParams('template',$template);
    }

    /**
     * @return string
     */
    public function render()
    {
        return Yii::$app->getView()->render($this->getView(),$this->getParams(),$this);
    }
    public function getViewPath(){
        return '@common/mail/render/views';
    }

    abstract function getView();
}