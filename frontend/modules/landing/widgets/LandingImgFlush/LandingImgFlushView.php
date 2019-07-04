<div class="ld-deal-banner">
    <div class="row">
        <?php if (!empty($images)) {
            foreach ($images as $key => $value) {
                ?>
                <?php if (count($images) == 1) { ?>
                    <div class="col-md-12">
                        <a href="<?php echo $value['link']; ?>" target="_blank"><img
                                    src="<?php echo $value['domain'] . $value['origin_src']; ?>"
                                    alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>"/>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-md-6">
                        <a href="<?php echo $value['link']; ?>" target="_blank"><img
                                    src="<?php echo $value['domain'] . $value['origin_src']; ?>"
                                    alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>"/>
                        </a>
                    </div>
                <?php } ?>
                <?php
            }
        } ?>
    </div>
</div>


