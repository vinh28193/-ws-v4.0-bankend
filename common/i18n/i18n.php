<?php

return [
    'class' => 'yii\i18n\I18N',
    'translations' => [
        '*' => [
            //'language' => 'vi-VN',
            'class' => 'yii\i18n\DbMessageSource',
            'sourceLanguage' => 'en-US', // Developer language
            'sourceMessageTable' => '{{%source_message}}', // '{{%language_source}}',
            'messageTable' => '{{%message}}',              // '{{%language_translate}}',
           // 'cachingDuration' => 86400,
            'enableCaching' => false,
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
