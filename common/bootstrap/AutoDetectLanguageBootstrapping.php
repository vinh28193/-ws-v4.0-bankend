<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 13:08
 */

namespace common\bootstrap;

use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\base\BootstrapInterface;
use common\i18n\I18nHelper;
use common\i18n\ChooseLanguage;

/**
 * Class AutoDetectLanguageBootstrapping
 *
 * mô tả
 *  đối với API Frontend v4 các User mặc định sẽ được coi là user hệ thống (User System) sẽ được coi như 1 Guest (bug nhiều user cùng tài khoảng
 *  nhưng khi đổi ngôn ngữ thì tất cả cũng đổi theo)
 * quy tắc save lưu file json theo key ([ChooseLanguage::buildName()])
 *  để thay đổi dưới dạng guest thì có thể set param ChooseLanguage[language] = 'vi' (en,id,my)
 * hoặc add http header Accept-Language:vi (en,id,my)
 * API frontend v4,
 * @package common\bootstrap
 */
class AutoDetectLanguageBootstrapping implements BootstrapInterface
{
    /**
     * @var \common\components\StoreManager|array|string the [[Store]] object or the application component ID of the [[StoreManager]]
     */
    public $storeManager = 'storeManager';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\console\Application|\yii\web\Application $app the application currently running
     * @throws \yii\base\InvalidConfigException
     * @throws \Throwable
     */
    public function bootstrap($app)
    {
        $this->storeManager = Instance::ensure($this->storeManager, 'common\components\StoreManager');
        if ($app instanceof \yii\web\Application) {
            if ($app->getUser()->getIsGuest()) {
                $languageChooser = new ChooseLanguage();
                if ($languageChooser->load($app->request->post()) && $languageChooser->saveLanguage()) {
                    I18nHelper::setLocale($languageChooser->language, $app);
                } else {
                    $language = $languageChooser->getSavedLanguage();
                    if ($language === null) {
                        // Use browser preferred language
                        $language = $app->request->getPreferredLanguage(I18nHelper::getSupportedLanguages());
                        if ($language === null) {
                            $language = $this->storeManager->getLanguageId();
                        }
                    }
                    I18nHelper::setLocale($language, $app);
                }
            } else {
                $user = $app->getUser()->getIdentity();
                if ($user->locale !== null && ArrayHelper::isIn($user->locale, I18nHelper::getSupportedLanguages())) {
                    I18nHelper::setLocale($user->locale, $app);
                } else {
                    I18nHelper::setLocale($this->storeManager->getLanguageId(), $app);
                }
            }

        } elseif ($app instanceof \yii\console\Application) {
            $app->language = 'en-US';
        }
    }
}