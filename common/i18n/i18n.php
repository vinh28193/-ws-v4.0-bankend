<?php

return [
    'class' => 'yii\i18n\I18N',
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceLanguage' => 'vi-VN', // Developer language
            'sourceMessageTable' => '{{%source_message}}', // '{{%language_source}}',
            'messageTable' => '{{%message}}',              // '{{%language_translate}}',
            'cachingDuration' => 86400,
            'enableCaching' => true,
            'db' => 'db',
            'forceTranslation' => true,
           // 'on missingTranslation' => ['common\i18n\TranslationEventHandler', 'handleMissingTranslation']
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
