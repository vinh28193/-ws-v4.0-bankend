<?php


namespace console\controllers;

use common\helpers\WeshopHelper;
use Yii;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\console\Controller;
use common\helpers\UtilityHelper;
use common\boxme\ZipCode;
use common\boxme\Location;
use common\models\db\SystemDistrict;
use common\models\db\SystemDistrictMapping;
use common\models\SystemZipcode;
use common\models\SystemStateProvince;

class ProvinceDistrictController extends Controller
{

    public $province;

    public $district;

    public $country;

    public static $trace = [];
    public static $error = [];


    public $color = true;

    public function options($actionID)
    {
        $options = parent::options($actionID);
        return array_merge($options, [
            'province', 'district', 'country'
        ]);
    }

    public function optionAliases()
    {
        $optionAliases = parent::optionAliases();
        return array_merge($optionAliases, [
            'p' => 'province',
            'd' => 'district',
            'c' => 'country'
        ]);
    }

    public function beforeAction($action)
    {
        self::$error = [];
        self::$trace = [];
        if ($this->country === null) {
            $this->stdout("    parameter -c(--country) must be set \n", Console::FG_RED);
            return false;
        }
        $this->country = WeshopHelper::strToUpperCase($this->country);
        if (!ArrayHelper::isIn($this->country, [Location::COUNTRY_ID, Location::COUNTRY_VN])) {
            $this->stdout(">    invalid parameter -c(--country) only effect id or vn \n", Console::FG_RED);
            return false;
        }
        $this->setDefaultHeaders();
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public function actionTruncate(){
        foreach ([SystemDistrict::tableName()] as $tableName){
            $sql =<<< SQL
SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE table `$tableName`; 
SET FOREIGN_KEY_CHECKS = 1;
SQL;
        Yii::$app->getDb()->createCommand($sql)->execute();
        }
    }
    /**
     * Tim kiếm và update quận huyện tỉnh thành và update theo dịa chỉ của boxme
     * Rule:
     *      - tìm được thì tự động map lại và tìm zipcode
     *      - cái nào thừa thì xóa đi (update hide)
     *      - cái nào thiếu thì tự thêm vào
     *
     * @return bool
     */
    public function actionIndex()
    {
        self::$error = [];
        self::$trace = [];

        if ($this->confirm("update for country: " . $this->country)) {
            $location = new Location();
            $countryId = $this->country === Location::COUNTRY_ID ? 2 : 1;
            $urlProvince = "https://s.boxme.asia/api/v1/locations/countries/{$this->country}/provinces/";
            $this->trace("create http request to $urlProvince", Console::FG_GREEN);
            $provinceLocation = $location->createHttpRequest($urlProvince);
            if ($provinceLocation === false) {
                return false;
            }
            $provinces = SystemStateProvince::find()->where([
                'AND',
                ['remove' => 0],
                ['country_id' => $countryId],
            ])->all();
            $provinceLocation = array_filter($provinceLocation, function ($raw) {
                return trim($raw['province_name']) !== '' && WeshopHelper::strToUpperCase($raw['province_name']) !== WeshopHelper::strToUpperCase('Ho Chi Minh');
            });
            $notFoundProvince = [];
            $provinceLocation = ArrayHelper::index($provinceLocation, function ($province) {
                return WeshopHelper::strToUpperCase($province['province_name']);
            });
            $totalProvince = count($provinces);
            foreach ($provinces as $province) {
                /** @var  $province SystemStateProvince */
                $start = microtime(true);
                $this->trace("set : $totalProvince", Console::FG_GREEN);
                $this->trace("updating info for province :{$province->name}({$province->id})", Console::FG_GREEN);
                $apiProvince = ArrayHelper::remove($provinceLocation, WeshopHelper::strToUpperCase($province->name));
                if ($apiProvince === null) {
                    $notFoundProvince[$province->id] = $province->name;
                    $error = "don not have boxme province {$province->name} (system id:{$province->id})";
                    $this->trace("$error", Console::FG_RED);
                    self::$error[] = $error;
                    continue;
                }
                $this->trace("founded api province :{$apiProvince['id']}:{$apiProvince['province_name']}", Console::FG_GREEN);
                $districts = SystemDistrict::find()->where([
                    'AND',
                    ['remove' => 0],
                    ['province_id' => $province->id],

                ])->all();
                $countDistrict = count($districts);
                $this->trace("province :{$province->name} have $countDistrict district", Console::FG_GREEN);
                $url = Location::DISTRICT_URL;
                $url = str_replace(['{country_code}', '{province_id}'], [$this->country, $apiProvince['id']], $url);
                $this->trace("create request to $url", Console::FG_GREEN);
                $apiDistricts = $location->createHttpRequest($url);
                $totalApiDistrictCount = count($apiDistricts);
                $this->trace("API return $totalApiDistrictCount district", Console::FG_GREEN);
                $apiDistricts = ArrayHelper::index($apiDistricts, function ($item) {
                    return WeshopHelper::strToUpperCase($item['district_name']);
                });
                if ($missing = abs($totalApiDistrictCount - $countDistrict) > 0) {
                    $error = "province {$province->id} {$province->name} missing $missing district";
                    $this->trace($error, Console::FG_RED);
                    self::$error[] = $error;
                }
                $notFoundDistrict = [];
                foreach ($districts as $district) {
                    /** @var  $district SystemDistrict */
                    $name = $this->replace($district->name);
                    $this->trace("{$province->name} ({$district->id}): updating district :$name({$district->id})", Console::FG_GREEN);
                    $apiDistrict = ArrayHelper::remove($apiDistricts, WeshopHelper::strToUpperCase($name));
                    if ($apiDistrict === null) {
                        $error = "don not have boxme district {$district->name} (system id:{$district->id}) of province {$province->name}";
                        $this->trace($error, Console::FG_RED);
                        self::$error[] = $error;
                        $notFoundDistrict[$district->id] = $district->name;
                        continue;
                    }
                    $this->updateDistrictInternal($district, $apiDistrict);
                    $district->updateAttributes([
                        'name_local' => trim($apiDistrict['district_name_local']),
                        'name_alias' => trim($apiDistrict['district_name_convert']),
                    ]);
                }
                if (count($notFoundDistrict) > 0) {
                    foreach ($notFoundDistrict as $id => $name) {
                        $this->trace("{$province->name} delete district $name ($id)", Console::FG_GREEN);
                        SystemDistrict::updateAll([
                            'remove' => 1,
                        ], ['id' => $id]);
                    }

                }
                if (count($apiDistricts) > 0) {
                    foreach ($apiDistricts as $newDistrict) {
                        $this->trace("{$province->name} insert new district {$newDistrict['district_name']}", Console::FG_GREEN);
                        $systemDistrict = new SystemDistrict();
                        $systemDistrict->name = trim($newDistrict['district_name']);
                        $systemDistrict->name_local = trim($newDistrict['district_name_local']);
                        $systemDistrict->name_alias = trim($newDistrict['district_name_convert']);
                        $systemDistrict->province_id = $province->id;
                        $systemDistrict->remove = 1;
                        $systemDistrict->version = '4.0';
                        $systemDistrict->display_order = 0;
                        $systemDistrict->save(false);
                        $systemDistrict->refresh();
                        $this->updateDistrictInternal($systemDistrict, $newDistrict);
                    }
                }
                $province->updateAttributes([
                    'name_local' => trim($apiProvince['province_name_local']),
                    'name_alias' => trim($apiProvince['province_name_convert'])
                ]);
                $time = microtime(true) - $start;
                $this->trace("time: " . sprintf('%.3f', $time) . "s", Console::FG_GREEN);
                $this->trace("=================================================", Console::FG_GREEN);
                $totalProvince--;
            }
            if (count($notFoundProvince) > 0) {
                foreach ($notFoundProvince as $id => $name) {
                    $this->trace("delete Province $name ($id)", Console::FG_GREEN);
                    SystemStateProvince::updateAll([
                        'Published' => 0
                    ], ['id' => $id]);
                }
            }
            if (count($provinceLocation) > 0) {
                $countTotalProvinceNotExist = count($provinceLocation);
                $this->trace("$countTotalProvinceNotExist not exist in system", Console::FG_GREEN);
                foreach ($provinceLocation as $newProvince) {
                    $this->trace("create new province {$newProvince['province_name']}", Console::FG_GREEN);
                    $provinceObject = new SystemStateProvince();
                    $provinceObject->country_id = $countryId;
                    $provinceObject->name = trim($newProvince['province_name']);
                    $provinceObject->name_local = trim($newProvince['province_name_local']);
                    $provinceObject->name_alias = trim($newProvince['province_name_convert']);
                    $provinceObject->display_order = 0;
                    $provinceObject->remove = 0;
                    $provinceObject->version = '4.0';
                    $provinceObject->save(false);
                    $provinceObject->refresh();
                    $url = Location::DISTRICT_URL;
                    $url = str_replace(['{country_code}', '{province_id}'], [$this->country, $newProvince['id']], $url);
                    $this->trace("create request to $url", Console::FG_GREEN);
                    $notApiDistricts = $location->createHttpRequest($url);
                    $totalApiDistrictCount = count($notApiDistricts);
                    $this->trace("API return $totalApiDistrictCount district", Console::FG_GREEN);
                    foreach ($notApiDistricts as $notApiDistrict) {
                        $this->trace("{$provinceObject->name} insert new district {$notApiDistrict['district_name']}", Console::FG_GREEN);
                        $systemDistrict = new SystemDistrict();
                        $systemDistrict->name = trim($notApiDistrict['district_name']);
                        $systemDistrict->name = trim($notApiDistrict['district_name_local']);
                        $systemDistrict->name_alias = trim($notApiDistrict['district_name_convert']);
                        $systemDistrict->province_id = $provinceObject->id;
                        $systemDistrict->remove = 0;
                        $systemDistrict->version = '4.0';
                        $systemDistrict->display_order = 0;
                        $systemDistrict->save(false);
                        $systemDistrict->refresh();
                        $this->updateDistrictInternal($systemDistrict, $notApiDistrict);
                    }
                }
            }
        }

    }

    public function actionArea()
    {
        self::$error = [];
        self::$trace = [];
        if ($this->confirm("update for country: " . $this->country)) {
            $location = new Location();
            $countryId = $this->country === Location::COUNTRY_ID ? 2 : 1;
            $urlProvince = "https://s.boxme.asia/api/v1/locations/countries/{$this->country}/provinces/";
            $this->trace("create http request to $urlProvince", Console::FG_GREEN);
            $provinceLocation = $location->createHttpRequest($urlProvince);
            if ($provinceLocation === false) {
                return false;
            }
            $province_ids = ArrayHelper::getColumn($provinceLocation, 'id', false);
            foreach ($province_ids as $id) {
                $start = microtime(true);
                $this->trace("proccess : $id", Console::FG_GREEN);
                $url = Location::DISTRICT_URL;
                $url = str_replace(['{country_code}', '{province_id}'], [$this->country, $id], $url);
                $this->trace("create http request to $url", Console::FG_GREEN);
                $apiDistricts = $location->createHttpRequest($url);
                $totalApiDistrictCount = count($apiDistricts);
                $this->trace("API return $totalApiDistrictCount district", Console::FG_GREEN);
                $raw = ArrayHelper::index($apiDistricts, null, 'area');
                foreach ($raw as $area => $array) {
                    $list = ArrayHelper::getColumn($array, 'id', false);
                    $countList = count($list);
                    $execute = SystemDistrictMapping::updateAll([
                        'area' => $area
                    ], ['box_me_district_id' => $list]);
                    $this->trace("district $id area $area have $countList query execute $execute on database", Console::FG_GREEN);
                }
            }
        }
    }

    public function actionTest()
    {

        if ($this->confirm("Your confirm before execute")) {
            $faker = <<<JSON
[{
    "id": 42,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Bảo Lâm",
    "district_name_local": "Huyện Bảo Lâm",
    "code": "",
    "district_name_convert": "huyenbaolam",
    "area": 0
},
{
    "id": 43,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Bảo Lạc",
    "district_name_local": "Huyện Bảo Lạc",
    "code": "",
    "district_name_convert": "huyenbaolac",
    "area": 0
},
{
    "id": 44,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Thông Nông",
    "district_name_local": "Huyện Thông Nông",
    "code": "",
    "district_name_convert": "huyenthongnong",
    "area": 0
},
{
    "id": 46,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Trà Lĩnh",
    "district_name_local": "Huyện Trà Lĩnh",
    "code": "",
    "district_name_convert": "huyentralinh",
    "area": 0
},
{
    "id": 47,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Trùng Khánh",
    "district_name_local": "Huyện Trùng Khánh",
    "code": "",
    "district_name_convert": "huyentrungkhanh",
    "area": 0
},
{
    "id": 48,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Hạ Lang",
    "district_name_local": "Huyện Hạ Lang",
    "code": "",
    "district_name_convert": "huyenhalang",
    "area": 0
},
{
    "id": 50,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Phục Hoà",
    "district_name_local": "Huyện Phục Hoà",
    "code": "",
    "district_name_convert": "huyenphuchoa",
    "area": 0
},
{
    "id": 51,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Hoà An",
    "district_name_local": "Huyện Hoà An",
    "code": "",
    "district_name_convert": "huyenhoaan",
    "area": 0
},
{
    "id": 52,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Nguyên Bình",
    "district_name_local": "Huyện Nguyên Bình",
    "code": "",
    "district_name_convert": "huyennguyenbinh",
    "area": 0
},
{
    "id": 40,
    "country_code": "VN",
    "province": 4,
    "district_name": "Thành phố Cao Bằng",
    "district_name_local": "Thành phố Cao Bằng",
    "code": "",
    "district_name_convert": "thanhphocaobang",
    "area": 1
},
{
    "id": 45,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Hà Quảng",
    "district_name_local": "Huyện Hà Quảng",
    "code": "",
    "district_name_convert": "huyenhaquang",
    "area": 0
},
{
    "id": 49,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Quảng Uyên",
    "district_name_local": "Huyện Quảng Uyên",
    "code": "",
    "district_name_convert": "huyenquanguyen",
    "area": 0
},
{
    "id": 53,
    "country_code": "VN",
    "province": 4,
    "district_name": "Huyện Thạch An",
    "district_name_local": "Huyện Thạch An",
    "code": "",
    "district_name_convert": "huyenthachan",
    "area": 0
}]
JSON;

            $faker = json_decode($faker, true);

            $faker = ArrayHelper::index($faker, function ($item) {
                return WeshopHelper::strToUpperCase($item['district_name']);
            });
            $lists = SystemDistrict::find()->where([
                'AND',
                ['province_id' => 1],
                ['remove' => 0]
            ]);
            $notFoundDistrict = [];
            foreach ($lists->all() as $row) {
                /** @var $row SystemDistrict */
                $apiDistrict = ArrayHelper::remove($faker, WeshopHelper::strToUpperCase($row->name));
                if ($apiDistrict === null) {
                    $this->stdout(">>> don not have boxme district {$row->name} (system id:{$row->id}) \n", Console::FG_RED);
                    $notFoundDistrict[$row->id] = $row->name;
                    continue;
                }
                $this->stdout("<<< founded boxme district {$row->name} (system id:{$row->id}) \n", Console::FG_GREEN);
            }

        }

    }

    protected function setDefaultHeaders()
    {
        header('Content-type: text/html; charset=utf-8');
    }

    /**
     * @param $district SystemDistrict
     * @param $apiResult
     */
    private function updateDistrictInternal($district, $apiResult)
    {

        if (($mapping = SystemDistrictMapping::findOne(['district_id' => $district->id])) === null) {
            $this->trace("create new mapping for: {$district->id}/{$district->province_id}", Console::FG_GREEN);
            $mapping = new SystemDistrictMapping([
                'district_id' => $district->id,
                'province_id' => $district->province_id,
                'box_me_province_id' => $apiResult['province'],
                'box_me_district_id' => $apiResult['id'],
            ]);
            $mapping->save(false);
        }
        $mapping->refresh();
        // Map lại
        $mapping->updateAttributes([
            'box_me_province_id' => $apiResult['province'],
            'box_me_district_id' => $apiResult['id'],
        ]);
        $this->trace("mapping district/province: {$district->id}/{$district->province_id} with :{$mapping->box_me_district_id}/{$mapping->box_me_province_id}", Console::FG_GREEN);
        if ($this->country === Location::COUNTRY_ID) {
            $zipCode = new ZipCode();
            $zipCode->district = $mapping->box_me_district_id;
            $zipCode->province = $mapping->box_me_province_id;
            if (count($availableZipCodes = $zipCode->getZipCode()) === 0) {
                $error = "not found zip code for district/province: {$district->id}/{$district->province_id} ({$mapping->box_me_district_id}/{$mapping->box_me_province_id})";
                $this->trace($error, Console::FG_RED);
                self::$error[] = $error;
            }
            $countZipCode = count($availableZipCodes);
            $this->trace("district/province {$district->id}/{$district->province_id} ({$mapping->box_me_district_id}/{$mapping->box_me_province_id}) have $countZipCode zip code", Console::FG_GREEN);
            if ($countZipCode > 0) {
                foreach ($availableZipCodes as $availableZipCode) {
                    $model = new SystemZipcode();
                    $model->system_country_id = 2;
                    $model->system_state_province_id = $district->province_id;
                    $model->system_district_id = $district->id;
                    $model->boxme_country_id = 2;
                    $model->boxme_state_province_id = $mapping->box_me_province_id;
                    $model->boxme_district_id = $mapping->box_me_district_id;
                    $model->zip_code = $availableZipCode;
                    $model->save(false);
                    $this->trace("insert mapping {$district->id}/{$district->province_id} and {$mapping->box_me_district_id}/{$mapping->box_me_province_id} zip code $availableZipCode", Console::FG_GREEN);
                }
            }
        }

    }

    public function afterAction($action, $result)
    {
        $path = Yii::getAlias('@runtime/data');
        if (!is_dir($path)) {
            FileHelper::createDirectory($path, 0777, true);
        }
        $errors = self::$error;
        self::$error = [];
        $traces = self::$trace;
        self::$trace = [];
        if (count($errors) > 0) {
            $errors = VarDumper::export($errors);
            $contentError = <<<EOD
<?php
    // error
    return $errors;
EOD;
            file_put_contents($path . "/{$action->id}_{$this->country}_error.php", $contentError, LOCK_EX);
        }
        if (count($traces) > 0) {
            $traces = VarDumper::export($traces);

            $contentTrace = <<<EOD
<?php
    // trace
    return $traces;
EOD;
            file_put_contents($path . "/{$action->id}_{$this->country}_trace.php", $contentTrace, LOCK_EX);
        }

        return parent::afterAction($action, $result);
    }


    private function trace($message, $helper)
    {
        self::$trace[] = $message;
        $this->stdout("    > $message \n", $helper);
    }

    public function replace($string)
    {
        if (ArrayHelper::isIn($string, [
            'Thành Phố Hòa Bình',
            'Huyện Hòa Vang',
            'Thị xã Ninh Hòa',
            'Huyện Hòa Thành',
            'Thành Phố Biên Hòa',
            'Huyện Đức Hòa',
            'Huyện Ứng Hòa',
            'Huyện Hiệp Hòa',
            'Huyện Đông Hòa',
            'Huyện Sơn Hòa'
        ])) {
            return $string;
        }
        return str_replace(
            ['Hòa', 'Áng', 'Quý', 'Huyện Vũng Liêm', 'Huyện Vị Thủy', 'Quận Bình Thủy', 'Huyện Thủy Nguyên', 'Huyện Kiến Thụy'],
            ['Hoà', 'Ảng', 'Quí', 'Huyện  Vũng Liêm', 'Huyện Vị Thuỷ', 'Quận Bình Thuỷ', 'Huyện Thuỷ Nguyên', 'Huyện Kiến Thuỵ']
            , $string);
    }
}