<?php


namespace common\mail;


abstract class BaseTarget extends \yii\base\BaseObject
{
    abstract function prepare(Template $template);
    abstract function handle(Template $template, $user);
}