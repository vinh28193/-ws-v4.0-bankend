<?php


namespace common\mail;

use yii\base\Component;

class MailManager extends Component
{

    private $_targets = [];

    public $_template;
}