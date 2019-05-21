<?php
//namespace frontend\modules\favorites\functions;
//use Yii;

/**
 * @param $obj_type
 * @param $obj_id
 *
 * @return bool
 */
function is_user_created_favorite($obj_type, $obj_id)
{
    if (Yii::$app->user->getId()) {
        return frontend\modules\favorites\models\Favorite::find()
            ->where(['obj_type' => $obj_type])
            ->andWhere(['obj_id' => $obj_id])
            ->andWhere(['created_by' => Yii::$app->user->getId()])
            ->exists();
    } else {
        /** ToDo @Phuc Save Cookies Web , APP ---> Mongodb **/
        return frontend\modules\favorites\models\Favorite::find()
            ->where(['obj_type' => $obj_type])
            ->andWhere(['obj_id' => $obj_id])
            ->andWhere(['ip' => Yii::$app->getRequest()->getUserIP()])
            ->exists();
    }
}

/**
 * @param $obj_type
 * @param $obj_id
 *
 * @return bool
 */
function create_favorite($obj_type, $obj_id)
{
    $getId =  Yii::$app->user->getId() ? Yii::$app->user->getId() : 'anonymous';
    $favorite = new frontend\modules\favorites\models\Favorite([
        'obj_type' => $obj_type,
        'obj_id' => $obj_id,
        'ip' => Yii::$app->getRequest()->getUserIP(),
        'created_by' => $getId,
    ]);
    if ($getId != 'anonymous') {
        if (is_user_created_favorite($obj_type, $obj_id)) {
            return true;
        } else {
            $favorite->created_by = Yii::$app->user->getId();
            return $favorite->save();
        }
    } else {
        if (is_user_created_favorite($obj_type, $obj_id)) {
            return true;
        } else {
            return $favorite->save();
        }
    }
}

/**
 * @param $obj_type
 * @param $obj_id
 *
 * @return bool
 */
function delete_favorite($obj_type, $obj_id)
{
    if (Yii::$app->user->getId()) {
        if (is_user_created_favorite($obj_type, $obj_id)) {
            return frontend\modules\favorites\models\Favorite::deleteAll([
                'obj_type' => $obj_type,
                'obj_id' => $obj_id,
                'created_by' => Yii::$app->user->getId(),
            ]);
        } else {
            return true;
        }
    } else {
        if (is_user_created_favorite($obj_type, $obj_id)) {
            return frontend\modules\favorites\models\Favorite::deleteAll([
                'obj_type' => $obj_type,
                'obj_id' => $obj_id,
                'ip' => Yii::$app->getRequest()->getUserIP(),
            ]);
        } else {
            return true;
        }
    }
}

/**
 * @param $obj_type
 * @param $obj_id
 *
 * @return int
 */
function delete_all_favorites($obj_type, $obj_id)
{
    return frontend\modules\favorites\models\Favorite::deleteAll([
        'obj_type' => $obj_type,
        'obj_id' => $obj_id
    ]);
}

/**
 * @param $obj_type
 * @param $obj_id
 * @return int|string
 */
function count_all_favorites($obj_type, $obj_id)
{
    return frontend\modules\favorites\models\Favorite::find()
        ->where(['obj_type' => $obj_type])
        ->andWhere(['obj_id' => $obj_id])
        ->count();
}
