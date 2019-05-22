<?php

namespace common\modelsMongo;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "favorites".
 *
 * @property integer $id
 * @property integer $obj_id
 * @property string $obj_type
 * @property string $ip
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class FavoritesMongoDB extends ActiveRecord
{
    public static function collectionName()
    {
        return ['Weshop_log_40', 'favorites'];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $reflection = new \ReflectionClass($this);
        if ($reflection->getShortName() === 'ActiveRecord') {
            return $behaviors;
        }

        $timestamp = [];
        if ($this->hasAttribute('created_at')) {
            $timestamp[self::EVENT_BEFORE_INSERT][] = 'created_at';
        }
        if ($this->hasAttribute('updated_at')) {
            $timestamp[self::EVENT_BEFORE_UPDATE][] = 'updated_at';
        }

        $behaviors = !empty($timestamp) ? array_merge($behaviors, [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => $timestamp,
            ],
        ]) : $behaviors;

        return $behaviors;
    }

    public function attributes()
    {
        return [
            '_id',
            'created_at',
            'updated_at',

            'created_by',
            'updated_by',

            'obj_id' ,
            'obj_type',
            'ip',
            ];
    }

    public function rules()
    {
        return [
            [['obj_id', 'obj_type', 'ip'], 'required'],
            [['obj_id', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [[ 'ip'], 'string', 'max' => 255],
            [['obj_type'],'safe']
            ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',

            'obj_id' => Yii::t('app', 'Obj ID'),
            'obj_type' => Yii::t('app', 'Obj Type là dạng serialize object'),
            'ip' => Yii::t('app', 'Ip'),

            'created_at'  => Yii::t('app', 'created at'),
            'updated_at'  => Yii::t('app', 'updated at'),
            'created_by'  => Yii::t('app', 'created by , là UUID thiết bị hoặc trình duyệt gửi lên'),
            'updated_by'  => Yii::t('app', 'updated by -->  là UUID thiết bị hoặc trình duyệt gửi lên'),

        ];
    }

    static public function search($params)
    {
        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');
        $search = Yii::$app->getRequest()->getQueryParam('search');
        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = FavoritesMongoDB::find()
            ->limit($limit)
            ->offset($offset);


        if (isset($order)) {
            $query->orderBy($order);
        }


        if (isset($order)) {
            $query->orderBy($order);
        }

        $additional_info = [
            'currentPage' => $page,
            'pageCount' => $page,
            'perPage' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = new \stdClass();
        $data->_items = $query->all();
        $data->_links = '';
        $data->_meta = $additional_info;
        return $data;

    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}
