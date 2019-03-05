<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/14/2017
 * Time: 9:29 AM
 */

namespace common\lib;


/**
 * @property string[] $categories;
 * @property [] $itemFilters;
 */
class EbaySearchForm
{
    public $website;
    public $categories;
    public $keywords;
    public $itemFilters = [];
    public $aspectFilters = [];
    public $page;
    public $limit;
    public $order;
    public $sellers;
    public $type;
    public $max_price;
    public $min_price;
    public $usTax;
    public $usShippingFee;
    public $itemsPerPage;
}
