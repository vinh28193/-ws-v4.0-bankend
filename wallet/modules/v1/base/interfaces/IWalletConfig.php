<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/24/2018
 * Time: 3:09 PM
 */

namespace wallet\modules\v1\base\interfaces;


interface IWalletConfig
{
    /**
     * Tạo access key truy cập ví
     * @return mixed
     */
    public function createAccessKey();

    public function createRequestKey();

    public function createClient();

    /**
     * Tao ma xac thuc otp
     * @return boolean
     */
    public function sentOtp();

    /**
     * @param $otp Mã otp
     * @return boolean
     */
    public function verifyOtp($otp);

}