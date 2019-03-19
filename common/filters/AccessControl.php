<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-19
 * Time: 14:28
 */

namespace common\filters;


class AccessControl extends \yii\filters\AccessControl
{

    /**
     * @var array
     */
    public $defaultRules = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->rules = array_merge($this->defaultRules, $this->rules);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function matchAction($action)
    {
        return empty($this->actions) || in_array($action->id, $this->actions, true);
    }
}