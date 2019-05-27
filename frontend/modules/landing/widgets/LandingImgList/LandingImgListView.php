<div class="banner-ld">
    <?php
    if (!empty($images)) {
        foreach ($images as $key => $value) {
            ?>
            <a href="<?php echo $value['link']; ?>" target="_blank"><img
                        src="<?php echo $value['domain'] . $value['origin_src']; ?>"
                        alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>"/>
            </a>
            <?php
        }
    } ?>
</div>