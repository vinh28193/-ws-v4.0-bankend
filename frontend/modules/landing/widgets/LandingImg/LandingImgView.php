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
                                    <a href="<?php echo $val->link; ?>" target="_blank"><img
                                                src="<?php echo $val->domain . $val->origin_src ?>"
                                                alt="<?php echo $val->name; ?>"></a>
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



