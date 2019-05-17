<?php


namespace common\payment\models;

use Yii;
use yii\base\Model;

class SecureForm extends Model
{

    public $email;
    public $password;
    public $rememberMe = true;
    public $isNew = 'yes';


}