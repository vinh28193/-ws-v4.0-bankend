<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/23/2018
 * Time: 4:47 PM
 */
namespace wallet\modules\v1\base\interfaces;

interface IBulk
{
    /**
     * Tăng điểm thưởng
     * @return mixed
     */
    public function topUpPoint();

    /**
     * Sử dụng điểm thưởng
     * @return mixed
     */
    public function useBulkPoint();

    /**
     * Lấy thông tin bulk
     * @return mixed
     */
    public function getBulkPoints();

    /**
     * Lấy tỉ giá điểm thưởng
     * @return mixed
     */
    public function getPointExRate();

}