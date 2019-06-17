<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: proto/courier.proto

namespace Courier;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Courier.CourierCalculateDiscountDetail</code>
 */
class CourierCalculateDiscountDetail extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>double courier_discount = 1;</code>
     */
    private $courier_discount = 0.0;
    /**
     * Generated from protobuf field <code>double coupon_discount = 2;</code>
     */
    private $coupon_discount = 0.0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type float $courier_discount
     *     @type float $coupon_discount
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Proto\Courier::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>double courier_discount = 1;</code>
     * @return float
     */
    public function getCourierDiscount()
    {
        return $this->courier_discount;
    }

    /**
     * Generated from protobuf field <code>double courier_discount = 1;</code>
     * @param float $var
     * @return $this
     */
    public function setCourierDiscount($var)
    {
        GPBUtil::checkDouble($var);
        $this->courier_discount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>double coupon_discount = 2;</code>
     * @return float
     */
    public function getCouponDiscount()
    {
        return $this->coupon_discount;
    }

    /**
     * Generated from protobuf field <code>double coupon_discount = 2;</code>
     * @param float $var
     * @return $this
     */
    public function setCouponDiscount($var)
    {
        GPBUtil::checkDouble($var);
        $this->coupon_discount = $var;

        return $this;
    }

}

