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
    }elseif (isset($item->description) && $item->description){
        if(is_array($item->description)){
            foreach ($item->description as $v){
                if(is_string($v)){
                    echo $v;
                }
            }
        }elseif (is_string($item->description)){
            echo $item->description;
        }
    }
}
?>