<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-06
 * Time: 13:37
 */

namespace common\mail;


class MailEvent extends \yii\base\Event
{

    public $template;

    public $targets;
    public $tagers;



}