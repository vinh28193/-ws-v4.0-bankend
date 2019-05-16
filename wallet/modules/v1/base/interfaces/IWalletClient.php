<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/24/2018
 * Time: 1:20 PM
 */

namespace wallet\modules\v1\base\interfaces;


use wallet\modules\v1\models\form\CreateWalletForm;

interface IWalletClient
{
    /**
     * Tạo ví
     * @param CreateWalletForm $form
     * @return mixed
     */
    public function createWallet($form);

    /**
     * Lấy thông tin ví
     * @return mixed
     */
    public function getWalletDetail();

    /**
     * Cập nhật thông tin ví
     * @return mixed
     */
    public function updateWalletInfo();

    /**
     * Xóa tài khoản ví
     * @return mixed
     */
    public function deleteWallet();


    /**
     * Chuyển tiền từ ví sang ví
     * @return mixed
     */
    public function transferWallet();

}