<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:52
 */

namespace common\models\boxme;


class ParcelForm
{
    /** @var ItemForm[] $items */
    public $items;
    public $weight;
    public $inspect_note;
    public $description;
    public $hs_code;
    public $dg_code;
    public $referral_code;
    /** @var string[] $images */
    public $images;
}