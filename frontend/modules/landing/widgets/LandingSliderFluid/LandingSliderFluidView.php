<?php

?>
<div class="main-tabs text-right">
    <div class="container">
        <ul class="list-inline">
            <?php if (isset($categories)) {
                foreach ($categories as $key => $cat) { ?>
                    <li class=""><a href="<?= $cat['url'] ?>"><?= $cat['name'] ?></a>
                    </li>
                <?php }
            } ?>

        </ul>
    </div>
</div>


<div id="ld-deal-slider" class="owl-carousel owl-theme ld-deal-slider">
    <?php if (isset($images)) {
        foreach ($images as $key => $value) { ?>
            <div class="item">
                <a href="<?=$value['link'];?>" class="image">
                    <img src="<?= $value['domain'] . $value['origin_src'] ?>" alt=""
                         title="">
                </a>
            </div>
        <?php }
    } ?>

</div>


