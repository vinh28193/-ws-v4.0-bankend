<?php

return [
    'class' => 'yii\i18n\I18N',
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'enableCaching' => true,
            'db' => 'db',
            'forceTranslation' => true,
//            'on missingTranslation' => ['common\i18n\TranslationEventHandler', 'handleMissingTranslation']
        ],
        'yii' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yii/messages',
            'fileMap' => [
                'yii' => 'yii.php',
            ],
        ],
    ],
];