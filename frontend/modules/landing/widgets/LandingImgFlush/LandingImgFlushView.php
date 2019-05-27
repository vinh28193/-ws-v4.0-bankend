<div class="ld-deal-banner">
    <div class="row">
        <?php if (!empty($images)) {
            foreach ($images as $key => $val) {
                ?>
                <?php if (count($images) == 1) { ?>
                    <div class="col-md-12">
                        <a href="<?php echo $val->link; ?>">
                            <img src="<?php echo $val->domain . $val->origin_src ?>" alt="<?php echo $val->name; ?>"
                                 title="<?php echo $val->name; ?>">
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-md-6">
                        <a href="<?php echo $val->link; ?>">
                            <img src="<?php echo $val->domain . $val->origin_src ?>" alt="<?php echo $val->name; ?>"
                                 title="<?php echo $val->name; ?>">
                        </a>
                    </div>
                <?php } ?>
                <?php
            }
        } ?>
    </div>
</div>


