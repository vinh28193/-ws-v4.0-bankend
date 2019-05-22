<?php

namespace common\models\db_cms;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $alias
 * @property int $siteId
 * @property string $name
 * @property string $originName
 * @property string $category_group_id
 * @property string $parentId
 * @property string $description
 * @property int $position
 * @property int $leaf
 * @property int $active
 * @property double $weight
 * @property double $interShippingB
 * @property double $customFee
 * @property double $customFeePercentage
 * @property int $home
 * @property string $createTime
 * @property string $updateTime
 * @property string $createEmail
 * @property string $updateEmail
 * @property int $icount
 * @property int $level
 * @property string $path
 * @property string $MetaKeywords Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property string $MetaDescription Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $MetaTitle Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property int $PageSize
 * @property double $priceMultiple He so gia
 * @property double $priceMultipleOld
 * @property double $priceAddition Phi van chuyen
 * @property double $priceAdditionOld
 * @property int $StoreId
 * @property int $taxRate
 * @property int $active_id
 * @property int $active_ph
 * @property int $active_my
 * @property int $active_sg
 * @property int $active_th
 * @property string $MetaDescription_id Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $MetaDescription_my Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $MetaDescription_ph Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $MetaDescription_sg Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $MetaDescription_th Những mô tả nhóm sản phẩm được hiện thị trong thẻ meta
 * @property string $name_vn
 * @property string $name_ph
 * @property string $name_th
 * @property string $name_sg
 * @property string $name_id
 * @property string $name_my
 * @property string $MetaTitle_id Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property string $MetaTitle_my Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property string $MetaTitle_ph Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property string $MetaTitle_sg Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property string $MetaTitle_th Tiêu đề sẽ được hiện thị trên header trình duyệt
 * @property string $MetaKeywords_id Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property string $MetaKeywords_my Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property string $MetaKeywords_ph Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property string $MetaKeywords_sg Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property string $MetaKeywords_th Những từ khóa của nhóm sản phẩm hiện thị trong thẻ meta
 * @property int $priority_weshopvn
 * @property int $priority_weshopmy
 * @property int $priority_weshopid
 * @property int $priority_weshopsg
 * @property int $priority_weshopph
 * @property int $priority_weshopth
 * @property int $L1CategoryId
 * @property int $L2CategoryId
 *
 * @property Store $store
 * @property Site $site
 */
class Category extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_cms');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siteId', 'category_group_id', 'position', 'leaf', 'active', 'home', 'icount', 'level', 'PageSize', 'StoreId', 'taxRate', 'active_id', 'active_ph', 'active_my', 'active_sg', 'active_th', 'priority_weshopvn', 'priority_weshopmy', 'priority_weshopid', 'priority_weshopsg', 'priority_weshopph', 'priority_weshopth', 'L1CategoryId', 'L2CategoryId'], 'integer'],
            [['description', 'MetaDescription', 'MetaDescription_id', 'MetaDescription_my', 'MetaDescription_ph', 'MetaDescription_sg', 'MetaDescription_th'], 'string'],
            [['weight', 'interShippingB', 'customFee', 'customFeePercentage', 'priceMultiple', 'priceMultipleOld', 'priceAddition', 'priceAdditionOld'], 'number'],
            [['createTime', 'updateTime'], 'safe'],
            [['alias', 'name', 'originName', 'path'], 'string', 'max' => 220],
            [['parentId'], 'string', 'max' => 25],
            [['createEmail', 'updateEmail'], 'string', 'max' => 100],
            [['MetaKeywords', 'MetaTitle', 'name_vn', 'name_ph', 'name_th', 'name_sg', 'name_id', 'name_my', 'MetaTitle_id', 'MetaTitle_my', 'MetaTitle_ph', 'MetaTitle_sg', 'MetaTitle_th', 'MetaKeywords_id', 'MetaKeywords_my', 'MetaKeywords_ph', 'MetaKeywords_sg', 'MetaKeywords_th'], 'string', 'max' => 255],
            [['StoreId'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['StoreId' => 'id']],
            [['siteId'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['siteId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'siteId' => 'Site ID',
            'name' => 'Name',
            'originName' => 'Origin Name',
            'category_group_id' => 'Category Group ID',
            'parentId' => 'Parent ID',
            'description' => 'Description',
            'position' => 'Position',
            'leaf' => 'Leaf',
            'active' => 'Active',
            'weight' => 'Weight',
            'interShippingB' => 'Inter Shipping B',
            'customFee' => 'Custom Fee',
            'customFeePercentage' => 'Custom Fee Percentage',
            'home' => 'Home',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
            'createEmail' => 'Create Email',
            'updateEmail' => 'Update Email',
            'icount' => 'Icount',
            'level' => 'Level',
            'path' => 'Path',
            'MetaKeywords' => 'Meta Keywords',
            'MetaDescription' => 'Meta Description',
            'MetaTitle' => 'Meta Title',
            'PageSize' => 'Page Size',
            'priceMultiple' => 'Price Multiple',
            'priceMultipleOld' => 'Price Multiple Old',
            'priceAddition' => 'Price Addition',
            'priceAdditionOld' => 'Price Addition Old',
            'StoreId' => 'Store ID',
            'taxRate' => 'Tax Rate',
            'active_id' => 'Active ID',
            'active_ph' => 'Active Ph',
            'active_my' => 'Active My',
            'active_sg' => 'Active Sg',
            'active_th' => 'Active Th',
            'MetaDescription_id' => 'Meta Description ID',
            'MetaDescription_my' => 'Meta Description My',
            'MetaDescription_ph' => 'Meta Description Ph',
            'MetaDescription_sg' => 'Meta Description Sg',
            'MetaDescription_th' => 'Meta Description Th',
            'name_vn' => 'Name Vn',
            'name_ph' => 'Name Ph',
            'name_th' => 'Name Th',
            'name_sg' => 'Name Sg',
            'name_id' => 'Name ID',
            'name_my' => 'Name My',
            'MetaTitle_id' => 'Meta Title ID',
            'MetaTitle_my' => 'Meta Title My',
            'MetaTitle_ph' => 'Meta Title Ph',
            'MetaTitle_sg' => 'Meta Title Sg',
            'MetaTitle_th' => 'Meta Title Th',
            'MetaKeywords_id' => 'Meta Keywords ID',
            'MetaKeywords_my' => 'Meta Keywords My',
            'MetaKeywords_ph' => 'Meta Keywords Ph',
            'MetaKeywords_sg' => 'Meta Keywords Sg',
            'MetaKeywords_th' => 'Meta Keywords Th',
            'priority_weshopvn' => 'Priority Weshopvn',
            'priority_weshopmy' => 'Priority Weshopmy',
            'priority_weshopid' => 'Priority Weshopid',
            'priority_weshopsg' => 'Priority Weshopsg',
            'priority_weshopph' => 'Priority Weshopph',
            'priority_weshopth' => 'Priority Weshopth',
            'L1CategoryId' => 'L1 Category ID',
            'L2CategoryId' => 'L2 Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'StoreId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'siteId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\cms\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\cms\CategoryQuery(get_called_class());
    }
}
