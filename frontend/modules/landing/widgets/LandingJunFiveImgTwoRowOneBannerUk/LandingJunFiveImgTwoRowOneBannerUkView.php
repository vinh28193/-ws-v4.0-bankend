<section id="style-6-container">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="uk-image5">
                    <a class="hover" href="<?php echo $images[4]['link'];?>">
                        <div class="information">
                            <?php echo !empty($images[4]['description'])?$images[4]['description']:''; ?>
                        </div>
                    </a>
                    <a href="<?php echo $images[4]['link'];?>">
                        <img class="img-responsive" src="<?php echo $images[4]['domain'] . $images[4]['origin_src']; ?>"
                             alt="">
                        <div class="clear"></div>
                    </a>
                </div>

            </div>

            <div class="col-xs-6">
                <div class="row">

                    <div class="col-xs-6">
                        <div class="uk-image1">
                            <a class="hover" href="<?php echo $images[0]['link'];?>">
                                <div class="information">
                                    <?php echo !empty($images[0]['description'])?$images[0]['description']:''; ?>
                                </div>
                            </a>
                            <a href="<?php echo $images[0]['link'];?>">
                                <img class="img-responsive" src="<?php echo $images[0]['domain'] . $images[0]['origin_src']; ?>"
                                     alt="">
                                <div class="clear"></div>
                            </a>
                        </div>

                        <div class="uk-image1">
                            <a class="hover" href="<?php echo $images[1]['link'];?>">
                                <div class="information">
                                    <?php echo !empty($images[1]['description'])?$images[1]['description']:''; ?>
                                </div>
                            </a>
                            <a href="<?php echo $images[1]['link'];?>">
                                <img class="img-responsive" src="<?php echo $images[1]['domain'] . $images[1]['origin_src']; ?>" alt="">
                                <div class="clear"></div>
                            </a>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="uk-image3">
                            <a class="hover" href="<?php echo $images[2]['link'];?>">
                                <div class="information">
                                    <?php echo !empty($images[2]['description'])?$images[2]['description']:''; ?>
                                </div>
                            </a>
                            <a href="<?php echo $images[2]['link'];?>">
                                <img class="img-responsive" src="<?php echo $images[2]['domain'] . $images[2]['origin_src']; ?>"
                                     alt="">
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xs-6 uk-image4">
                <a class="hover" href="<?php echo $images[4]['link'];?>">
                    <div class="information">
                        <?php echo !empty($images[4]['description'])?$images[4]['description']:''; ?>
                    </div>
                </a>
                <a href="<?php echo $images[4]['link'];?>">
                    <img src="<?php echo $images[4]['domain'] . $images[4]['origin_src']; ?>" alt=""
                         class="img-responsive">
                </a>
            </div>
        </div>

    </div>
</section>
