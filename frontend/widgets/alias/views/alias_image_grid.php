<?php

/* @var $this yii\web\View */
/* @var $wsImageGroups array */
?>


<div class="left">
    <ul>
        <?php for ($i = 0; $i < 4; $i++):
            $image = $wsImageGroups['wsImages'][$i];
            if ((!empty($image['domain']) && !empty($image['origin_src']))) {
                $link = $image['domain'] . $image['origin_src'];
            }
            ?>
            <li>
                <a href="<?php echo isset($image['link']) ? $image['link'] : '' ?>">
                    <img src="<?= $link; ?>" alt="" title="">
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</div>
<div class="right">
    <ul>
        <?php for ($i = 4; $i < 7; $i++):
            $image = $wsImageGroups['wsImages'][$i];
            if ((!empty($image['domain']) && !empty($image['origin_src']))) {
                $link = $image['domain'] . $image['origin_src'];
            }
            ?>
            <li>
                <a href="<?php echo isset($image['link']) ? $image['link'] : '' ?>">
                    <img src="<?= $link; ?>" alt="" title="">
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</div>
