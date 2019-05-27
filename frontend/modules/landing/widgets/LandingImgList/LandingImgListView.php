<div class="banner-ld">
    <?php
    if (!empty($images)) {
        foreach ($images as $key => $value) {
            ?>
            <a href="<?php echo $value->link; ?>">
                <img src="<?php echo $value->domain . $value->origin_src ?>" alt="<?php echo $value->name; ?>">
            </a>
            <?php
        }
    } ?>
</div>