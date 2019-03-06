<?php

return [
    'class' => 'yii\i18n\I18N',
    'translations' => [
        '*' => [
            'class' => 'common\i18n\WeShopMessageSource',
            'enableCaching' => true,
            'db' => 'db',
            'cache' => [
                'class' => 'common\components\FileCache',
                'keyPrefix' => 'i18n',
                'directoryLevel' => 0,
                'cachePath' => '@i18nCache'
            ],
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