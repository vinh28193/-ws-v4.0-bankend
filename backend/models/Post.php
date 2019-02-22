<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%post}}';
    }

    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            ['status', 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_ACTIVE]],
            ['status', 'default', 'value' => self::STATUS_DRAFT],
            [['title'], 'string', 'max' => 255],
        ];
    }

    static public function search($params)
    {

        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }


        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;


        $offset = ($page - 1) * $limit;

        $query = Employee::find()
            ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->asArray(true)
            ->limit($limit)
            ->offset($offset);

        if(isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if(isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if(isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if(isset($params['name'])) {
            $query->andFilterWhere(['like', 'name', $params['name']]);
        }
        if(isset($params['email'])){
            $query->andFilterWhere(['like', 'email', $params['email']]);
        }


        if(isset($order)){
            $query->orderBy($order);
        }


        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        return [
            'data' => $query->all(),
            'info' => $additional_info
        ];
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $this->created_at = date("Y-m-d H:i:s", time());
                $this->updated_at = date("Y-m-d H:i:s", time());

            } else {

                $this->updated_at = date("Y-m-d H:i:s", time());
            }
            return true;
        } else {
            return false;
        }


    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ACTIVE => 'Active',
        ];
    }

    public function publish()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            throw new \DomainException('Post is already published.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->status == self::STATUS_DRAFT) {
            throw new \DomainException('Post is already drafted.');
        }
        $this->status = self::STATUS_DRAFT;
    }
}
