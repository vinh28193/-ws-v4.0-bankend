<?php

Yii::setAlias('@root', dirname(dirname(__DIR__)));

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@wallet', dirname(dirname(__DIR__)) . '/wallet');
Yii::setAlias('@userbackend', dirname(dirname(__DIR__)) . '/userbackend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@i18nCache', dirname(dirname(__DIR__)) . '/i18n-cache');
Yii::setAlias('@landing', '@frontend/modules/landing');

Yii::setAlias('@weshop', '@backend/modules/weshop');
Yii::setAlias('@weshop/payment', '@weshop/weshop-payment/src');

Yii::setAlias('@protobufboxme', dirname(dirname(__DIR__)) . '/protobufboxme');

//Yii::setAlias('@mdm/admin' , '@app/extensions/mdm/yii2-admin-2.0.0');
