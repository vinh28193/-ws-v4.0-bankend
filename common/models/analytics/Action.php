<?php
/**
 * Created by PhpStorm.
 * User: vinhvv@peacesoft.net
 * Date: 2/9/18
 * Time: 10:44 AM
 */

namespace common\models\analytics;

/**
 * Class Action
 * Represents information about an ecommerce related action that has taken place. It is referred to as an actionFieldObject
 * @package mongosoft\soapclient\common\models\analytics
 */
class Action extends DataType
{
    /**
     * A click on a product or product link for one or more products.
     */
    const ACTION_CLICK = 'click';

    /**
     * A view of product details.
     */
    const ACTION_DETAIL = 'detail';

    /**
     * Adding one or more products to a shopping cart.
     */
    const ACTION_ADD = 'add';

    /**
     * Remove one or more products from a shopping cart.
     */
    const ACTION_REMOVE = 'remove';

    /**
     * Initiating the checkout process for one or more products.
     */
    const ACTION_CHECKOUT = 'checkout';

    /**
     * Sending the option value for a given checkout step.
     */
    const ACTION_CHECKOUT_OPTION = 'checkout_option';

    /**
     * The sale of one or more products.
     */
    const ACTION_PURCHASE = 'purchase';

    /**
     * The refund of one or more products.
     */
    const ACTION_REFUND = 'refund';

    /**
     * A click on an internal promotion.
     */
    const ACTION_PROMO_CLICK = 'promo_click';

    /**
     * The transaction ID
     * (e.g. T1234).
     *  Required if the action type is purchase or refund.
     * @var string
     */
    public $id;

    /**
     * The store or affiliation from which this transaction occurred '(e.g. Google Store).
     * @var string
     */
    public $affiliation = 'Weshop VietNan';

    /**
     * Currency
     * Specifies the total revenue or grand total associated with the transaction
     * (e.g. 11.99).
     * This value may include shipping, tax costs, or other adjustments to
     * total revenue that you want to include as part of your revenue calculations.
     * Note: if revenue is not set, its value will be automatically calculated using
     * the product quantity and price fields of all products in the same hit.
     * @var string|integer
     */
    public $revenue = 0;

    /**
     * Currency
     * The total tax associated with the transaction.
     * @var string|integer
     */
    public $tax = 0;

    /**
     * Currency
     * The shipping cost associated with the transaction.
     * @var string|integer
     */
    public $shipping = 0;

    /**
     * The transaction coupon redeemed with the transaction.
     * @var string
     */
    public $coupon = '';

    /**
     * The list that the associated products belong to.
     * Optional.
     * @var string
     */
    public $list = 'Search Result';
    /**
     * A number representing a step in the checkout process.
     * Optional on checkout actions.
     * @var integer
     */
    public $step;
    /**
     * Additional field for checkout and checkout_option actions
     * that can describe option information on the checkout page,
     * like selected payment method.
     * @var string
     */
    public $option;
}