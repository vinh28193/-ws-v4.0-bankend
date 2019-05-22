<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use Yii;
use yii\helpers\Json;
use common\models\db_cms\CategoryGroup as DbCmsCategoryGroup;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;

class CategoryGroup extends DbCmsCategoryGroup
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const NAME_WATCH = 'WATCH';
    const NAME_CAMERA = 'CAMERA';
    const NAME_CAMERA_AND_CAMERA_LENSES = 'CAMERA_AND_CAMERA_LENSES';
    const NAME_CAMERA_LENSES = 'CAMERA_LENSES';
    const NAME_PHONE = 'PHONE';
    const NAME_CELL_PHONE = 'CELL_PHONE';
    const NAME_OTHER_PHONE = 'OTHER_PHONE';
    const NAME_LAPTOP = 'LAPTOP';
    const NAME_APPLE_ALIEN_WARE = 'APPLE_ALIEN_WARE';
    const NAME_OTHER_LAPTOP = 'OTHER_LAPTOP';
    const NAME_SPEAKER_AMPLY = 'SPEAKER_AMPLY';
    const NAME_ELECTRONICS = 'ELECTRONICS';
    const NAME_IPOD = 'IPOD';
    const NAME_DANGEROUS_GOODS = 'DANGEROUS_GOODS';
    const NAME_DJ_GOODS = 'DJ_GOODS';
    const NAME_GOLF_CLUBS = 'GOLF_CLUBS';
    const NAME_MONITORS = 'MONITORS';

    const NAME_TABLET = 'TABLET';
    const NAME_IPAD = 'IPAD';

    const NAME_OFFICE_MACHINE = 'OFFICE_MACHINE';
    const NAME_OPTICAL_INSTRUMENT = 'OPTICAL_INSTRUMENT';


    /**
     * @param  $target
     * @return float|int
     */
    public function customFeeCalculator(AdditionalFeeInterface $target)
    {
        if ($target->getCustomCategory()->siteId != '1014') {
            $start = microtime(true);
            $totalCustomFee = 0;
            $name = $this->name;
            $price = $target->getTotalOriginPrice()[0];
            $quantity = $target->getShippingQuantity();
            $weight = $target->getShippingWeight();
            $isNew = $target->getIsNew();
            $token = 'Custom fee begin calculator , is new:' . ($isNew ? 'true' : 'false');
            if ($this->name === self::NAME_WATCH) {
                $token .= ', in Watch';
                if ($price <= 250) {
                    $token .= ' <= 250';
                    $totalCustomFee = 6; // 6$/ chiếc - Đồng hồ dưới 250$
                } elseif (250 < $price && $price <= 500) {
                    $token .= '  > 250 && <= 500';
                    $totalCustomFee = 12; // 12$/chiếc - Đồng hồ từ 250$ - 500$
                } else {
                    $token .= '  > 500';
                    if ($isNew) {
                        $totalCustomFee = $price * 0.07; // 7% - Đồng hồ mới, giá trị trên 500$
                    } else {
                        $totalCustomFee = $price * 0.1; // 10% - Đồng hồ cũ, giá trị trị trên 500$
                    }
                }
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_CAMERA_AND_CAMERA_LENSES || $name === self::NAME_CAMERA || $name === self::NAME_CAMERA_LENSES) {
                $token .= ', in Camera';
                if ($name === self::NAME_CAMERA && $price < 250) {
                    $token .= ' < 250';
                    $totalCustomFee = 10; // 10$/cái -  Camera < 250$
                } elseif ($name === self::NAME_CAMERA_LENSES && $price < 250) {
                    $totalCustomFee = 5; // 5$/cái - Lenses 250$
                    $token .= ' Lenses < 250';
                } else {
                    $token .= '/Lenses > 250';
                    $totalCustomFee = $price * 0.05; // 5% - Camera/Lense > 250$
                }
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_PHONE || $name === self::NAME_CELL_PHONE || $name === self::NAME_OTHER_PHONE) {
                $token .= ', in Phone';
                $totalCustomFee = 25; //25$/cái - Tất cả các loại (new) - Thực tế thu thêm là 40$, 55$, 80$... nên sale sẽ tự tạo thêm
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_LAPTOP || $name === self::NAME_APPLE_ALIEN_WARE || $name === self::NAME_OTHER_LAPTOP) {
                $token .= ', in Laptop';
                if ($name === self::NAME_LAPTOP && $isNew) {
                    $token .= '(normal - new or like new)';
                    $totalCustomFee = 50 > ($percent = ($price * 0.07)) ? 50 : $percent; // 50$/cái hoặc 7% -  Laptop (hàng new hoặc like new) - 50$/cái hoặc 7% tùy theo cái nào cao hơn
                } elseif ($name === self::NAME_APPLE_ALIEN_WARE && $isNew) {
                    $token .= '(macbook pro, imac ... - new or like new)';
                    $totalCustomFee = 80 > ($percent = ($price * 0.07)) ? 80 : $percent; // 80$/cái hoặc 7% -  Macbook pro, imac, alienware (hàng new hoặc like new)  - 80$/cái hoặc 7% tùy theo cái nào cao hơn
                } else {
                    $token .= '(other laptop)';
                    $totalCustomFee = $price < 1000 ? 40 : $price * 0.1; // 40$/cái hoặc 10% - Laptop thường, hàng cũ - 40$/cái (Dưới 1000$) hoặc 10% nếu từ 1000$
                }
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_SPEAKER_AMPLY) {
                $token .= ', in Speaker';
                $totalCustomFee = $price * 0.05; // 5%
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_ELECTRONICS && $price > 250) {
                $token .= ', in Electronics > 250';
                $totalCustomFee = $price * 0.05; // 5%
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_IPOD) {
                $token .= ', in Ipod';
                $totalCustomFee = $price * 0.1; // 10%
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_DANGEROUS_GOODS && $weight > 0.8) {
                $token .= ', in Dangerous goods';
                $totalCustomFee = $weight * 1; // 1$/kg Phân bón, dầu oil, nước tẩy rửa, hóa chất dạng liquid
                $token .= ', weight ' . $weight;
            } else if ($name === self::NAME_DJ_GOODS) {
                $token .= ', in Dj goods';
                if ($price <= 250) {
                    $token .= ' <= 250';
                    $totalCustomFee = 10; //10$/cái
                } else {
                    $token .= ' > 250';
                    $totalCustomFee = $price * 0.05;
                }
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_GOLF_CLUBS) {
                $token .= ', in Gold Clubs';
                $totalCustomFee = 15; // 15$
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_MONITORS) {
                $token .= ', in Monitors';
                $totalCustomFee = $price * 0.05; // 5%
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_IPAD) {
                $token .= ', in Ipad';
                $totalCustomFee = 50; // 50$
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_TABLET) {
                $token .= ', in Tablet';
                $totalCustomFee = 40; // 40$
                $token .= ', quantity ' . $quantity;
                $totalCustomFee = $totalCustomFee * $quantity;
            } else if ($name === self::NAME_OFFICE_MACHINE || $name === self::NAME_OPTICAL_INSTRUMENT) {
                $token .= ', in';
                $token .= $name === self::NAME_OFFICE_MACHINE ? 'Office Machine' : 'Optical Instrument';
                if ($price < 345) {
                    $totalCustomFee = 10; // dưới 345$ phụ thu 10$
                } else if (345 <= $price && $price < 1000) {
                    $totalCustomFee = $price * 0.05; // từ 345$ - dưới 1000$ phụ thu 5%
                } else {
                    $totalCustomFee = $price * 0.07; // từ 1000$ trở lên 7%
                }
                $totalCustomFee = $totalCustomFee * $quantity;
            }
            $time = microtime(true) - $start;
            $token .= ', us price ' . $price . ', custom fee: ' . $totalCustomFee . ', calculate ended (time: ' . sprintf('%.3f', $time) . ' s)';
            Yii::info($token, 'CUSTOM FEE INFORMATION');
            return $totalCustomFee;
        } else {
            if ($this->rule === null || $this->rule === '') {
                return 0.0;
            }
            $rules = Json::decode($this->rule, true);
            return CalculatorService::calculator($rules, $target);
        }

    }
}
