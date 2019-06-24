<?php

namespace frontend\modules\favorites\controllers;

//use frontend\modules\favorites\Favorite;
use Yii;
use yii\base\ErrorException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\controllers\FrontendController;
use frontend\modules\favorites\models\Favorite;
use common\modelsMongo\FavoritesMongoDB;

use common\products\BaseProduct;


/**
 * Default controller for the `CommentModule` module
 */
class FavoriteObject
{
    public $uuid ;
    public function init()
    {}

    /**
     * @param $obj_type
     * @param $obj_id
     *
     * @return integer
     * @throws ErrorException
     */
    public function Create($item, $id, $uuid)
    {
        /* @var BaseProduct $item */
        /* @var common\components\StoreManager $storeManager */

        $data_favorite = new \stdClass();
        $data_favorite->category_id = $item->category_id;
        $data_favorite->item_name = isset($item->item_name) ? $item->item_name : '' ;
        $data_favorite->salePercent = 0  ;
        $data_favorite->providers = $item->providers ;
        $data_favorite->item_origin_url = $item->item_origin_url ;
        $data_favorite->primary_images = isset($item->primary_images[0]) ? $item->primary_images[0]->main : '/img/no_image.png'  ; // $item->primary_images ;
        $data_favorite->old_price = $item->getLocalizeTotalStartPrice() ;
        $data_favorite->type =  isset($item->type) ? $item->type : ''  ;
        $data_favorite->item_id = isset($item->item_id) ? $item->item_id : ''  ;
        $data_favorite->start_price = isset($item->start_price) ? $item->start_price : 0  ;
        $data_favorite->buynow_price = $item->getLocalizeTotalPrice()  ;
        $data_favorite->sell_price = $item->getSellPrice() ;
        $data_favorite->retail_price = isset($item->retail_price) ? $item->retail_price : 0  ;


        Yii::info([
            'data' =>  $data_favorite,
            'action' => 'Create'
        ], __CLASS__);

        if ($item->getLocalizeTotalPrice() > 0 and $item->getSellPrice() > 0) {
            if ($this->create_favorite($data_favorite, $id, $uuid)) {
                Yii::info("app  create favorite Success");
                return 1;
            } else {
                Yii::info(" app Can't create favorite");
                // throw new ErrorException(Yii::info('app', "Can't create favorite"));
                return 0;
            }
        } else {
            Yii::info(" Gía bán hoặc giá gốc sản phẩm < = 0");
        }
    }

    /**
     * @param $obj_type
     * @param $obj_id
     *
     * @return Yii\web\Response
     * @throws ErrorException
     */
    public function Delete($obj_type, $obj_id)
    {
        if ($this->delete_favorite($obj_type, $obj_id)) {
            Yii::info("app  Delete favorite Success");
            //return $this->goBack();
        } else {
            throw new ErrorException(Yii::t('app', "Can't delete favorite"));
        }
    }

    public function getfavorite($uuid)
    {
        if (Yii::$app->user->getId() === $uuid) {
            // Login
                try {
                    return $this->get_favorite($uuid);
                } catch (\Exception $exception) {
                    Yii::info($exception);
                    return false;
                }
        } else {
            // anonymous
            try {
                 return $this->get_favorite($uuid);
             } catch (\Exception $exception) {
                    Yii::info($exception);
                    return false;
             }
        }
    }

    /**
     * @param $id
     *
     * @return Favorite|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Favorite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * @param $uuid
     * @return bool
     */
    function get_favorite($uuid)
    {
        if (Yii::$app->user->getId()) {
            if( $count = Favorite::find()->where(['created_by' => Yii::$app->user->getId()])->exists())
            {
                return  Favorite::find()
                         ->where(['created_by' => Yii::$app->user->getId()])
                         ->all();
            }else { return false;}
        } else {
            return FavoritesMongoDB::find()
                ->where(['created_by' => $uuid])
                //->where(['ip' => Yii::$app->getRequest()->getUserIP()])
                ->all();
        }
    }

    /**
     * @param $obj_type
     * @param $obj_id
     *
     * @return bool
     */
    function is_user_created_favorite($obj_type, $obj_id)
    {
        if (Yii::$app->user->getId()) {
            $data_find = Favorite::find()
                //->where(['obj_type' => $obj_type])
                ->where(['obj_id' => $obj_id])
                ->andWhere(['created_by' => Yii::$app->user->getId()])
                ->exists();
            Yii::info("Find db Mysql Favorite ");
            Yii::info([
                'data_find_Favorite' => $data_find,
            ], __CLASS__);
            return $data_find;
        } else {
            /** ToDo Done 22/5/2019 @Phuc Save UUID Web , APP ---> Mongodb **/
            $data_find_mongo = FavoritesMongoDB::find()
                ->where(['obj_type' => $obj_type])
                ->andWhere(['obj_id' => $obj_id])
                ->andWhere(['ip' => Yii::$app->getRequest()->getUserIP()])
                ->exists();
            Yii::info("Find db Mysql FavoritesMongoDB ");
            Yii::info([
                'data_find_Favorite' => $data_find_mongo,
            ], __CLASS__);
            return $data_find_mongo;
        }
    }

    /**
     * @param $obj_type
     * @param $obj_id
     *
     * @return bool
     */
    function create_favorite($obj_type, $obj_id, $UUID)
    {
        // Todo @Huy edit thuộc tính insert lại DB + Mongodb
        if (Yii::$app->user->getId() === $UUID) {
            // Login
            $getId =  Yii::$app->user->getId() ? Yii::$app->user->getId() : '9999';
            $favorite = new Favorite([
                'obj_type' => @serialize($obj_type),
                'obj_id' => $obj_id,
                'ip' => Yii::$app->getRequest()->getUserIP(),
                'created_by' => $getId,
            ]);

            Yii::info([
                'Favorite_new' => $favorite,
            ], __CLASS__);

            // Check exits
            if ($this->is_user_created_favorite($obj_type, $obj_id)) {
                return true;
            } else {
                $favorite->created_by = Yii::$app->user->getId();
                try {
                    $favorite->validate();
                    Yii::info($favorite->getErrors());
                    $favorite->save();
                    return true;
                } catch (\Exception $exception) {
                    Yii::info($exception);
                    return false;
                }
            }
        } else {
            // anonymous --> All Over right uuid if null
            // Todo Error UUID khoong la duy nhat

            $uuid = isset($UUID) ? $UUID : $this->uuid;
            $favoriteMongodb = new FavoritesMongoDB([
                'obj_type' => @serialize($obj_type),
                'obj_id' => $obj_id,
                'ip' => Yii::$app->getRequest()->getUserIP(),
                'created_by' => $uuid,
            ]);

            if ($this->is_user_created_favorite($obj_type, $obj_id)) {
                    return true;
             } else {
                try {
                        $favoriteMongodb->validate();
                        Yii::info($favoriteMongodb->getErrors());
                        $favoriteMongodb->save();
                        return true;
                } catch (\Exception $exception) {
                        Yii::info($exception);
                        return false;
                }
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
            if ($this->is_user_created_favorite($obj_type, $obj_id)) {
                return Favorite::deleteAll([
                    'obj_type' => $obj_type,
                    'obj_id' => $obj_id,
                    'created_by' => Yii::$app->user->getId(),
                ]);
            } else {
                return true;
            }
        } else {
            if ($this->is_user_created_favorite($obj_type, $obj_id)) {
                return Favorite::deleteAll([
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
        return Favorite::deleteAll([
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
        return Favorite::find()
            ->where(['obj_type' => $obj_type])
            ->andWhere(['obj_id' => $obj_id])
            ->count();
    }
}
