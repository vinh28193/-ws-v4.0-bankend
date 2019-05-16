<?php


namespace common\payment\models;

use Yii;
use yii\base\Model;

class SecureForm extends Model
{

    public $loginId;
    public $password;
    public $isNew = 'yes';
    public $rememberMe = true;


}