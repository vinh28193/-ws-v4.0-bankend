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
    <div class="detail-block-2" id="description_<?= $type ?>" style="box-shadow: 0 2px 4px #dddddd;padding: 15px">
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
            <div class="col-md-12" style="margin:0px;padding:0px;overflow:hidden">
                <?php
                if($checkShow){
                    if(strtolower($item->type) =='amazon'){
                        if($type == 'extra'){
                            if(isset($item->manufacturer_description) && $item->manufacturer_description){
                                echo $item->manufacturer_description;
                            }
                        }else{
                            if(isset($item->sort_desc) && $item->sort_desc){
                                echo $item->sort_desc;
                            }elseif($item->description){
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
                    }else{
                        ?>
                        <!--<iframe style="border: 0px; width: inherit; height: auto; overflow: hidden"
                                onload="autoHeightIframe(this)"
                                src="https://vi.vipr.ebaydesc.com/ws/eBayISAPI.dll?ViewItemDescV4&item=<?/*= ($item->item_id) */?>"
                                frameborder="0"  sandbox="allow-forms allow-scripts" scrolling="yes"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>-->
                        <iframe src="https://vi.vipr.ebaydesc.com/ws/eBayISAPI.dll?ViewItemDescV4&item=<?= ($item->item_id) ?>"
                                frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
                        <!--<iframe style="border: 0px; width: inherit; height: auto; overflow: hidden"
                                onload="autoHeightIframe(this)"
                                src="/description/<?/*= strtolower($item->type) */?>-<?/*= ($item->item_id) */?>.html?description=<?/*= $type */?>"
                                frameborder="0"  sandbox="allow-forms allow-scripts" scrolling="yes"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>-->
                        <?php
                    }
                }
                 ?>
            </div>
        </div>
    </div>