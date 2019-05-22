<?php

namespace frontend\modules\favorites;

use Yii;
use frontend\modules\favorites\base\Favorite as BaseFavorite;

/**
 * This is the model class for table "favorites".
 */
class Favorite extends BaseFavorite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['obj_id', 'obj_type', 'ip'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ip',], 'string', 'max' => 255],
            [['obj_type'],'safe'],
            [['obj_id'],'string']
        ]);
    }

}
