<?php
/**
 * Created by PhpStorm.
 * User: vinhvv@peacesoft.net
 * Date: 2/9/18
 * Time: 10:02 AM
 */

namespace common\models\analytics;

/**
 * Class Product
 * Product data represents individual products that were viewed, added to the shopping cart, etc. It is referred to as a productFieldObject.
 * @see productFieldObject https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#product-data
 * @package common\models\analytics;
 *
 */
class ProductAnalytics extends DataType
{
    /**
     * Product list Ebay
     */
    const EBAY = 'EBAY';

    /**
     * Product list Amazon
     */
    const AMZON = 'AMAZON';

    /**
     * Product list Amazon_JB
     */
    const AMAZONJB = 'AMAZON-JB';


    /**
     * Product list UK
     */
    const ORTHERUK = 'UK';

    /**
     * The product ID or SKU
     * (e.g. P67890).
     * Either this field must be set.
     * @var string
     */
    public $id;

    /**
     * The name of the product
     * (e.g. Android T-Shirt).
     * Either this field must be set.
     * @var string
     */
    public $name;

    /**
     * The brand associated with the product
     * (e.g. Google).
     * @var string
     */
    public $brand = '';

    /**
     * The category to which the product belongs
     * (e.g. Apparel).
     * Use / as a delimiter to specify up to 5-levels of hierarchy
     * (e.g. Apparel/Men/T-Shirts).
     * @var string
     */
    public $category = '';

    /**
     * The variant of the product
     * (e.g. Black).
     * @var string
     */
    public $variant = '';

    /**
     * Currency
     * The price of a product
     * (e.g. 29.20).
     * @var string|integer
     */
    public $price = 0;

    /**
     * The quantity of a product
     * (e.g. 2).
     * @var integer
     */
    public $quantity = 1;

    /**
     * The coupon code associated with a product
     * (e.g. SUMMER_SALE13).
     * @var string
     */
    public $coupon = '';

    /**
     * The product's position in a list or collection
     * (e.g. 2).
     * @var integer
     */
    public $position = 1;

    /**
     * impressionFieldObject contains the following values
     * The list or collection to which the product belongs
     * (e.g. Search Results)
     * @var string
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#impression-data
     */
    public $list = self::EBAY;
}