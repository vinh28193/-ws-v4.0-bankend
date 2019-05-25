<?php
/**
 *
 * @var $item \common\products\BaseProduct
 */
$typeDes = Yii::$app->request->get('description','extra');
if($typeDes == 'extra'){
    echo isset($item->manufacturer_description) && $item->manufacturer_description ? $item->manufacturer_description : '';
}else{
    if(isset($item->sort_desc) && $item->sort_desc){
        echo $item->sort_desc;
    }
}
?>