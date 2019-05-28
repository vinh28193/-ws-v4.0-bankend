<?php

namespace common\queue;

use Yii;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Default controller for the `CommentModule` module
 */
class PurcachseQueue extends BaseObject implements JobInterface
{
    public $_to_token_fingerprint;
    public $_title;
    public $_body;
    public $_click_action;


    public function execute($queue)
    {
        Yii::info("Start execute app  create Purchase ");
        Yii::info(" _to_token_fingerprint : " . $this->_to_token_fingerprint);
        Yii::info(" _body : ". $this->_body);

        Yii::$app->wsFcnApn->Create($_to_token_fingerprint = 'f42w7MQMVIU:APA91bFSWXH6PLBNgvZTXIS2gm4_QM3Lc-El46dokbqJXXtY8zv8oMaNd4B8LYOTgILSl38COdPQRY_ajdUJoecy6jSxy7O6CUOATTHMt9NqGZRu-W1018mvLzJaf4Cj1z2lSt38o5gG',
            $_title = 'Purchase success! PO-9090' ,
            $_body = 'Purchase success! PO-9090',
            $_click_action='https://admin.weshop.asia');
    }


}
