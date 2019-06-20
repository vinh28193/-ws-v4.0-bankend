<?php
/**
 * @var $type string
 * @var $item \common\products\BaseProduct
 */
$checkShow = false;
if($type == 'extra'){
    if(isset($item->manufacturer_description) && $item->manufacturer_description){
        $checkShow = true;
    }
}else{
    if(isset($item->sort_desc) && $item->sort_desc){
        $checkShow = true;
    }elseif($item->description){
            $checkShow = true;
    }
}
    ?>
    <div class="detail-block-2" id="description_<?= $type ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?=Yii::t('frontend',$type == 'extra' ? 'Product details' : 'Product description'); ?>:</div>
            </div>
            <div class="col-md-12 row">
                <?php
                if($type == 'extra' && $item->technical_specific){
                    foreach ($item->technical_specific as $value) {?>
                        <div class="col-md-6 row">
                            <div class="col-md-6"><b><?= $value->name ?>:</b></div>
                            <div class="col-md-6"><?= $value->value ?></div>
                        </div>
                    <?php }
                } ?>
            </div>
            <div class="col-md-12">
                <?php
                if($checkShow){?>
                    <iframe style="border: 0px; width: inherit; height: -webkit-fill-available; max-height: 500px"
                            src="/description/<?= strtolower($item->type) ?>-<?= ($item->item_id) ?>.html?description=<?= $type ?>"
                            frameborder="0"  sandbox="allow-forms allow-scripts" scrolling="yes"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                <?php } ?>
            </div>
        </div>
    </div>
