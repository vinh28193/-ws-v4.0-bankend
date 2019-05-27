
<section id="style-2-container">
    <div class="container">
        <ul>
            <div class="row mkt-landing-flex">
                <li class="col-xs-12 col-md-4 landing-mkt-6" style="width: 33%">
                    <a class="hover" href="<?php echo $images[0]['link'];?>">
                        <div class="information">
                            <?php echo !empty($images[0]['description'])?$images[0]['description']:'';?>
                        </div>
                    </a>
                    <a href="<?php echo $images[0]['link'];?>">
                        <img class="img-responsive" src="<?php echo $images[0]['domain'].$images[0]['origin_src'];?>" alt="">
                    </a>
                </li>
                <li class="col-xs-12 col-md-4 landing-mkt-6">
                    <a class="hover" href="<?php echo $images[1]['link'];?>">
                        <div class="information">
                            <?php echo !empty($images[1]['description'])?$images[1]['description']:'';?>
                        </div>
                    </a>
                    <a href="<?php echo $images[1]['link'];?>">
                        <img class="img-responsive" src="<?php echo $images[1]['domain'].$images[1]['origin_src'];?>" alt="">
                    </a>
                </li>
                <li class="col-xs-12 col-md-4 landing-mkt-6">
                    <a class="hover" href="<?php echo $images[2]['link'];?>">
                        <div class="information">
                            <?php echo !empty($images[2]['description'])?$images[2]['description']:'';?>
                        </div>
                    </a>
                    <a href="<?php echo $images[2]['link'];?>">
                        <img class="img-responsive" src="<?php echo $images[2]['domain'].$images[2]['origin_src'];?>" alt="">
                    </a>
                </li>
            </div>
        </ul>
    </div>
</section>