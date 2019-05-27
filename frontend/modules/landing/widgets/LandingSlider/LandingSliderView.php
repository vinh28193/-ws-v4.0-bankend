<?php


?>
<div class="main-tabs text-right">
    <div class="container">
        <ul class="list-inline">
            <?php if (isset($categories)) {
                foreach ($categories as $key => $cat) { ?>
                    <li class=""><a href="<?=$cat['url']?>"><?= $cat['name']?></a>
                    </li>
                <?php }
            } ?>

        </ul>
    </div>
</div>

<div class="lmkt-head">
    <div id="lmkt-slider" class="owl-carousel">
        <?php if (isset($images)) {
            foreach ($images as $key => $value) { ?>
                <div class="item">
                    <a href="<?= $value['link'] ?>">
                        <img src="<?= $value['domain'] . '/' . $value['origin_src'] ?>" alt="<?= $value['name'] ?>" title="<?= $value['name'] ?>">
                    </a>
                </div>

            <?php }
        } ?>
    </div>
</div>
<script>
    $('#lmkt-slider').owlCarousel({
        stagePadding: 150,
        loop:true,
        margin:10,
        nav:false,
        items:1,
        lazyLoad: true,
        nav:true,
        responsive:{
            0:{
                items:1,
                stagePadding: 60
            },
            600:{
                items:1,
                stagePadding: 100
            },
            1000:{
                items:1,
                stagePadding: 200
            },
            1200:{
                items:1,
                stagePadding: 250
            },
            1400:{
                items:1,
                stagePadding: 300
            },
            1600:{
                items:1,
                stagePadding: 350
            },
            1800:{
                items:1,
                stagePadding: 400
            }
        }
    })
</script>
