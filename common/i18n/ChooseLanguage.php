<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-08
 * Time: 08:59
 */

namespace common\i18n;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ChooseLanguage extends Model
{
    const COOKIE_NAME = 'guest_language_choosed';
    /**
     * @var string the language
     */
    public $language;

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            ['language', 'in', 'range' => I18nHelper::getSupportedLanguages()],
            ['language', 'filter', 'filter' => '\common\i18n\I18nHelper::fixedLocale'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array(
            'language' => Yii::t('base', 'Language'),
        );
    }

    protected function saveToStorage(){
        if (Yii::$app->getUser()->getIsGuest() || (($user = Yii::$app->getUser()->getIdentity()) !== null && $this->isUserSystem($user))){
            I18nHelper::setSavedLanguage($this->getKeyToSave(),$this->language,self::COOKIE_NAME);
        } else{
            /** @var  $user \common\models\model\Customer*/
            $user = Yii::$app->getUser()->getIdentity();
            if($user->locale !== $this->language){
                $this->language = $this->language === 'en-US' ? 'en' : $this->language;
                $user->updateAttributes(['locale' => $this->language]);
            }
        }
//
//        $cookie = new \yii\web\Cookie([
//            'name' => self::COOKIE_NAME,
//            'value' => $this->language,
//            'expire' => time() + 86400 * 365,
//        ]);
//        Yii::$app->getResponse()->getCookies()->add($cookie);
    }
    /**
     * Stores language as cookie
     *
     * @since 1.2
     * @return boolean
     */
    public function saveLanguage()
    {
        if ($this->validate()) {
            $this->saveToStorage();
            return true;
        }

        return false;
    }

    /**
     * Returns the saved language in the cookie
     *
     * @return string the stored language
     */
    public function getSavedLanguage()
    {

        if (Yii::$app->getUser()->getIsGuest() || (($user = Yii::$app->getUser()->getIdentity()) !== null && $this->isUserSystem($user))){
            if(($language = I18nHelper::getSavedLanguage($this->getKeyToSave(), self::COOKIE_NAME)) !== null){
                $this->language = $language;
                if (!$this->validate()) {
                    // Invalid data
                    $this->language = I18nHelper::fixedLocale('en');
                    $this->saveToStorage();
                } else {
                    return $this->language;
                }
            }
        }else{
            return Yii::$app->getUser()->getIdentity()->locale;
        }

//        if (isset(Yii::$app->request->cookies[self::COOKIE_NAME])) {
//            $this->language = (string)Yii::$app->request->cookies[self::COOKIE_NAME];
//
//            if (!$this->validate()) {
//                // Invalid cookie
//                $this->language = I18nHelper::fixedLocale('en');
//                $this->saveToStorage();
//            } else {
//                return $this->language;
//            }
//        }

        return null;
    }

    public function getKeyToSave(){
        return I18nHelper::buildName(null);
    }

    public function isUserSystem($user){
        if(method_exists($user,'isSystemAccount')){
            return $user->isSystemAccount();
        }
        return ArrayHelper::isIn($user->getId(),[1,2,3]);
    }
}