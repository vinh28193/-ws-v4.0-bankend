<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@userbackend', dirname(dirname(__DIR__)) . '/userbackend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@i18nCache', dirname(dirname(__DIR__)) . '/i18n-cache');


Yii::setAlias('@weshop','@backend/modules/weshop');
Yii::setAlias('@weshop/payment','@weshop/weshop-payment/src');

//Yii::setAlias('@mdm/admin' , '@app/extensions/mdm/yii2-admin-2.0.0');