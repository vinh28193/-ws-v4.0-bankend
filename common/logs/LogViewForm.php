<?php


namespace common\logs;


use yii\base\Model;

class LogViewForm extends Model
{
    public $create_time;
    public $message;
    public $code_reference;
    public $type_log;
    public $user_name;
    public $user_email;
}