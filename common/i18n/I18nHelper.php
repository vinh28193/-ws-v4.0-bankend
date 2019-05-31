<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-08
 * Time: 09:02
 */

namespace common\i18n;

use common\components\StoreManager;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

class I18nHelper
{

    /**
     * @return StoreManager
     */
    public static function getStoreManager()
    {
        return Yii::$app->storeManager;
    }

    public static function translate($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, $language);
    }

    public static function getAllMessage($category, $language)
    {
        $i18n = Yii::$app->getI18n();
        $messageSource = $i18n->getMessageSource($category);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public static function getDefautLanguage($store = null)
    {
        if ($store === null) {
            return ArrayHelper::getValue(Yii::$app->params, 'defaultLanguage', 'en-US');
        } elseif ($store instanceof \common\components\StoreManager) {
            return $store->getLanguageId();
        } elseif (is_numeric($store)) {
            $storeManager = self::getStoreManager();
            $storeManager->storeId = $store;
            return $storeManager->getLanguageId();
        } else {
            throw new \InvalidArgumentException('Invalid argument for $store');
        }
    }

    /**
     * @param $locale
     * @param null|\yii\web\Application|\yii\console\Application $instance
     */
    public static function setLocale($locale, $instance = null)
    {
        if ($instance === null) {
            $instance = Yii::$app;
        }
        if ($locale !== null) {
            $instance->language = $locale;
            $instance->formatter->locale = $locale;
        }

    }

    public static function fixedLocale($locale)
    {
        if ($locale === 'en' || $locale === null) {
            $locale = 'en-US';
        }
        return $locale;
    }

    public static function isExpired($time)
    {
        return Yii::$app->formatter->asTimestamp('now') < $time;
    }

    /**
     * @param bool $raw
     * @return array|mixed
     */
    public static function getSupportedLanguages($raw = false)
    {
        $supportedLanguages = require Yii::getAlias('@common/i18n/languages.php');
        return $raw ? $supportedLanguages : ArrayHelper::getColumn($supportedLanguages, 'language', false);
    }

    public static function setSavedLanguage($name, $language, $fileName = 'save_language')
    {
        $allContents = self::getSavedLanguage(null, $fileName);
        if (($currentContents = ArrayHelper::getValue($allContents, $name)) !== null) {
            list($oldLanguage, $time) = $currentContents;
            if (self::isExpired($time) || $oldLanguage !== $language) {
                $allContents[$name] = [
                    $language,
                    Yii::$app->formatter->asTimestamp('now + 2 days')
                ];
            }
        } else {
            $allContents[$name] = [
                $language,
                Yii::$app->formatter->asTimestamp('now + 2 days')
            ];
        }

        return file_put_contents(self::savePath($fileName), json_encode($allContents));
    }

    /**
     * @param null $name
     * @param string $fileName
     * @return bool|mixed|null
     */
    public static function getSavedLanguage($name, $fileName = 'save_language')
    {
        $fileName = self::savePath($fileName);
        if (!file_exists($fileName)) {
            file_put_contents($fileName, json_encode([]));
        }
        if (($contents = file_get_contents($fileName, FILE_USE_INCLUDE_PATH)) !== false) {
            $contents = json_decode($contents, true);
            return $name !== null ? ArrayHelper::getValue($contents, $name, null) : $contents;
        }
        return null;
    }

    public static function savePath($fileName = 'save_language', $mimeType = 'application/json')
    {
        $cachePath = Yii::getAlias('@runtime/i18n');
        FileHelper::createDirectory($cachePath, 0777);
        $fileExtension = count($ext = FileHelper::getExtensionsByMimeType($mimeType)) > 1 ? $ext : 'json';
        return $cachePath . '/' . $fileName . '.' . $fileExtension;
    }

    public static function buildName($store = null, $reference = null)
    {
        if ($reference === null) {
            $reference = Inflector::camelize(Yii::$app->getRequest()->getUserIP());
        }
        if ($store === null) {
            $store = self::getStoreManager()->storeId;
        } elseif ($store instanceof \common\components\StoreManager) {
            $store = $store->storeId;
        }
        return $store . $reference;
    }
}