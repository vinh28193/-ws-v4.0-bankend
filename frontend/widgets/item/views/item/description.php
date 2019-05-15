<?php
/**
 * @var $item \common\products\BaseProduct
 */

?>
<div class="detail-block-2">
    <div class="row">
        <div class="col-md-12">
            <div class="title">Mô tả sản phẩm:</div>
        </div>
        <div class="col-md-12">
            <iframe width="853" height="480" src="/description/<?= strtolower($item->type) ?>-<?= strtolower($item->item_id) ?>.html" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>
<div class="detail-block-2">
    <div class="row">
        <div class="col-md-12">
            <div class="title">Chi tiết sản phẩm:</div>
        </div>
        <div class="col-md-12">
            <iframe width="853" height="480" src="/description/<?= strtolower($item->type) ?>-<?= strtolower($item->item_id) ?>.html" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>