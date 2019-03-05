<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 10:54
 */

namespace common\data;

use Yii;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{

    /**
     * @inheritdoc
     * @param array|bool|\yii\data\Pagination $value
     * @throws \yii\base\InvalidConfigException
     */
    public function setPagination($value)
    {
        $config = ['class' => Pagination::className()];
        if ($this->id !== null) {
            $config['pageParam'] = $this->id . '-page';
            $config['pageSizeParam'] = $this->id . '-per-page';
        }
        $config = Yii::createObject(array_merge($config, $value));

        parent::setPagination($config);
    }
}