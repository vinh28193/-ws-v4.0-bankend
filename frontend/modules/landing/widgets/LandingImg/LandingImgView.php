<div class="lts-banner-all">
    <div class="lts-title">
        <h3></h3>
    </div>
    <div class="lts-banner">
        <div class="container">
            <ul class="col-xs-12">
                <div class="row">
                    <?php
                    if (!empty($images)) {
                        foreach ($images as $key => $val) {
                            ?>

                            <li class="col-xs-6">
                                <div class=" lts-banner-detail">
                                    <a href="<?php echo $value['link']; ?>" target="_blank"><img
                                                src="<?php echo $value['domain'] . $value['origin_src']; ?>"
                                                alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>"/>
                                    </a>
                                </div>
                            </li>

                            <?php
                        }
                    }
                    ?>
                </div>
            </ul>
        </div>
    </div>
</div>



