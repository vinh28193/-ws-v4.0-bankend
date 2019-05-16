<?php
/**
 * Created by PhpStorm.
 * User: tunglt
 * Date: 20/12/2017
 * Time: 11:39 AM
 */

namespace common\models\payment\indonesia\nicepay;

use common\models\enu\BasicEnum;

abstract class InstalmentConfig extends BasicEnum
{
    const BANK_BMRI = 'BMRI';
    const AMOUNT_REQUIRED = 500000;
    const PAYMENT_METHOD = 59;
}