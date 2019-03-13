<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-13
 * Time: 20:11
 */

namespace common\filters;

class VerbFilter extends \yii\filters\VerbFilter
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $actions = [];
        foreach ($this->actions as $id => $verbs){
            $verbs[] = 'OPTIONS';
            $actions[$id] = $verbs;
        }
        $this->actions = $actions;
    }
}