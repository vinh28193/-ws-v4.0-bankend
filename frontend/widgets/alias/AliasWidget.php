<?php

namespace frontend\widgets\alias;

use common\models\cms\PageService;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Widget;
use yii\helpers\ArrayHelper;

class AliasWidget extends Widget
{

    public $type;

    public $isShow = false;

    private $_alias;

    public function init()
    {
        parent::init();
        if (empty($this->type)) {
            throw new InvalidConfigException("You must set the 'type' property");
        }
        $this->getAlias();
    }

    public function run()
    {
        parent::run();
        $viewParams = ArrayHelper::merge([
            'type' => $this->type,
            'isShow' => $this->isShow
        ], $this->getAlias());
        /* @var $type string */
        /* @var $isShow boolean */
        /* @var $alias array */
        /* @var $landing array */
        /* @var $categories array */
        /* @var $images array */
        return $this->render('alias',$viewParams);
    }

    protected function getAlias()
    {
        if (!$this->_alias) {
            $this->_alias = PageService::getAlias($this->type);
        }
        return $this->_alias;
    }
}