<?php


namespace common\models\draft;


class DraftDataTrackingSearch
{
    public $limit = 20;
    public $page = 1;
    public $param = 1;
    public function search(){
        $modal = DraftDataTracking::find();

    }

}