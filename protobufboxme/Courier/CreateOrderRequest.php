<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: proto/courier.proto

namespace Courier;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *&#47;//////////////////
 *
 * Generated from protobuf message <code>Courier.CreateOrderRequest</code>
 */
class CreateOrderRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string Data = 1;</code>
     */
    private $Data = '';
    /**
     * Generated from protobuf field <code>int32 UserId = 2;</code>
     */
    private $UserId = 0;
    /**
     * Generated from protobuf field <code>string CountryCode = 3;</code>
     */
    private $CountryCode = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $Data
     *     @type int $UserId
     *     @type string $CountryCode
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Proto\Courier::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string Data = 1;</code>
     * @return string
     */
    public function getData()
    {
        return $this->Data;
    }

    /**
     * Generated from protobuf field <code>string Data = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setData($var)
    {
        GPBUtil::checkString($var, True);
        $this->Data = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 UserId = 2;</code>
     * @return int
     */
    public function getUserId()
    {
        return $this->UserId;
    }

    /**
     * Generated from protobuf field <code>int32 UserId = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setUserId($var)
    {
        GPBUtil::checkInt32($var);
        $this->UserId = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string CountryCode = 3;</code>
     * @return string
     */
    public function getCountryCode()
    {
        return $this->CountryCode;
    }

    /**
     * Generated from protobuf field <code>string CountryCode = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setCountryCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->CountryCode = $var;

        return $this;
    }

}

