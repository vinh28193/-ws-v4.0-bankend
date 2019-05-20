<?php
/**
 * @var $title string
 * @var $stepUrl array
 */
?>

<div class="be-content-header">
    <div class="be-title"><?= $title ?></div>
    <nav aria-label="breadcrumb" class="be-breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($stepUrl as $step => $url) {
                ?>
            <li class="breadcrumb-item"><a href="<?= $url ?>"><?= $step ?></a></li>
            <?php }?>
        </ol>
    </nav>
</div>
