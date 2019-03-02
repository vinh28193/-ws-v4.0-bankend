<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 01/03/2019
 * Time: 16:38
 */
namespace api\modules\v1\api\models;
use yii\base\Model;

class OrderSearchForm
{
    public $typeOrder;
    public $keyword;
    public $typeSearch;
    public $timeStart;
    public $timeEnd;
    public $status;
    public $typeTime;
    public $limit;
    public $page;

    public function __construct($requestType = "get")
    {
        if($requestType == 'get'){
            $request = \Yii::$app->request->get();
        }else{
            $request = \Yii::$app->request->post();
        }
        $this->typeOrder = isset($request['typeOrder']) ? $request['typeOrder'] : null;
        $this->keyword = isset($request['keyword']) ? $request['keyword'] : "";
        $this->typeSearch = isset($request['typeSearch']) ? $request['typeSearch'] : null;
        $this->timeStart = isset($request['timeStart']) ? $request['timeStart'] : null;
        $this->timeEnd = isset($request['timeEnd']) ? $request['timeEnd'] : null;
        $this->typeTime = isset($request['typeTime']) ? $request['typeTime'] : null;
        $this->status = isset($request['status']) ? $request['status'] : null;
        $this->limit = isset($request['limit']) ? $request['limit'] : 20;
        $this->page = isset($request['page']) ? $request['page'] : 1;
    }

}