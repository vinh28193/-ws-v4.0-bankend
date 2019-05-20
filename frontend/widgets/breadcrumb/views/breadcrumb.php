<?php

/**
 * đặt sau thẻ div có class là container
 * @var $portal string
 */
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/">Weshop Global</a></li>
        <li class="breadcrumb-item"><a href="/<?= strtolower($portal); ?>.html"> <?php if( strtolower($portal) == "ebay") { echo 'Shop Ebay';}  if( strtolower($portal) == "amazon") { echo 'Shop Amazon';}  if( strtolower($portal) == "amazon-jp") { echo 'Shop Amazon Japan';} if( strtolower($portal) == "amazon-uk") { echo 'Shop Amazon Japan';} ?>   </a></li>
        <?= $Ekey = Yii::$app->request->get('keyword','') ?>
        <?php if ($Ekey != ''){?>
            <li class="breadcrumb-item active">Tìm kiếm từ khóa <?php  echo $Ekey; ?></li>
        <?php } ?>
    </ol>
</nav>
