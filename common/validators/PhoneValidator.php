<?php


namespace common\validators;

use common\components\StoreManager;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\validators\RegularExpressionValidator;


class PhoneValidator extends RegularExpressionValidator
{

    public $exceptNetworks = [];
    public $phoneFormat = '/^(\+?84|\+?62|0)?(\d+)$/';

    public function init()
    {
       $country = $this->getStoreManager()->store->country_code;
        if (!in_array($country, array_keys($this->getNetworkByCountry()))) {
            throw new InvalidConfigException("Your country $country setup is not valid!");
        }
        $networks = $this->getNetworkByCountry()[$country];
        if (!empty($networks)) {
            $patterns = [];
            foreach ($networks as $name => $pattern) {
                if (ArrayHelper::isIn($name, $this->exceptNetworks)) {
                    continue;
                }
                $patterns[] = $pattern;
            }
            if (!empty($pattern)) {
                $this->pattern = "~(" . implode(")|(", $patterns) . ")~";
            }
        }
        parent::init();
        $this->message = Yii::t('validator', '{attribute} is invalid in country {country}.',[
            'country' => $country
        ]);
    }

    public function getNetworkByCountry()
    {
        return [
            'VN' => [
                'viettel' => '^(\+?84|0)(3[2-9]|86|9[6-8])\d{7}$',
                'vinaPhone' => '^(\+?84|0)(8[1-5]|88|9[14])\d{7}$',
                'mobiFone' => '^(\+?84|0)(70|7[6-9]|89|9[03])\d{7}$',
                'vietNamMobile' => '^(\+?84|0)(5[68]|92)[\d]{7}$',
                'gMobile' => '^(\+?84|0)([59]9|95)[\d]{7}$',
                'default' => '^(\+?84|0)?(((20[3-9]|21[0-6]|21[89]|22[0-2]|22[5-9]|23[2-9]|24[2-5]|248|25[12]|25[4-9]|26[0-3]|27[0-7]|28[2-5]|29([0-4]|[67])|299)\d{7})|((246[236]|247[13]|286[23]|287[13])\d{6}))$'
            ],
            'ID' => [
                'all' => '\+62\s\d{3}[-\.\s]??\d{3}[-\.\s]??\d{3,4}|\(0\d{2,3}\)\s?\d+|0\d{2,3}\s?\d{6,7}|\+62\s?361\s?\d+|\+62\d+|\+62\s?(?:\d{3,}-)*\d{3,5}'
            ]
        ];
    }

    /** @var StoreManager */
    private $_storeManager;

    /**
     * @return StoreManager|mixed
     */
    public function getStoreManager()
    {
        if(!is_object($this->_storeManager)){
            $this->_storeManager = Yii::$app->storeManager;
        }
        return $this->_storeManager;
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if (($result = parent::validateAttribute($model, $attribute)) === null) {
            if ($this->phoneFormat !== null) {
                $model->{$attribute} = preg_replace($this->phoneFormat, '0$2', $model->{$attribute});
            }
        }
        return $result;
    }
}