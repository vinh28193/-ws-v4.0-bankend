<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:41
 */

namespace common\models\boxme;


class ShipInfoForm
{
    public $contact_name = "";
    public $company_name = "";
    public $address = "";
    public $address2 = "";
    public $phone = "";
    public $phone2 = "";
    public $country_id = ""; // boxme_id
    public $province_id = ""; // boxme_id
    public $district_id = ""; // boxme_id
    public $pickup_id = ""; // boxme_id
    public $zipcode = "";
}