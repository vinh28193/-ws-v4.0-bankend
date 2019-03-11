<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-09-07
 * Time: 16:21
 */

namespace common\i18n;


class WeShopMessageSource extends \yii\i18n\DbMessageSource
{

    /**
     * Loads the message translation for the specified language and category.
     * If translation for specific locale code such as `en-US` isn't found it
     * tries more generic `en`.
     *
     * @param string $category the message category
     * @param string $language the target language
     * @return array the loaded messages. The keys are original messages, and the values
     * are translated messages.
     */
    public function loadMessages($category, $language)
    {
        return parent::loadMessages($category, $language);
    }
}