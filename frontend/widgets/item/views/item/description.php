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
    }else{
        if($item->description){
            $checkShow = true;
        }
    }
}
    ?>
    <div class="detail-block-2">
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?= $type == 'extra' ? 'Chi tiết sản phẩm' : 'Mô tả sản phẩm:' ?></div>
            </div>
            <div class="col-md-12">
                <?php
                if($checkShow){?>
                    <iframe height="480" style="width: inherit"
                            src="/description/<?= strtolower($item->type) ?>-<?= strtolower($item->item_id) ?>.html?description=<?= $type ?>"
                            frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                <?php } ?>
            </div>
        </div>
    </div>