<?php

/**
 * đặt sau thẻ div có class là container
 * @var $portal string
 */
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/"><?=Yii::t('frontend','Home');?></a></li>
        <li class="breadcrumb-item"><a href="/"><?=Yii::t('frontend','Weshop Global');?></a></li>
        <li class="breadcrumb-item"><a href="/<?= strtolower($portal); ?>.html"> <?php if( strtolower($portal) == "ebay") { echo 'Shop Ebay';}  if( strtolower($portal) == "amazon") { echo 'Shop Amazon';}  if( strtolower($portal) == "amazon-jp") { echo 'Shop Amazon Japan';} if( strtolower($portal) == "amazon-uk") { echo 'Shop Amazon Japan';} ?>   </a></li>
        <?= $Ekey = Yii::$app->request->get('keyword','') ?>
        <?php if ($Ekey != ''){?>
            <li class="breadcrumb-item active"><?=Yii::t('frontend','Search keyword {keyword}',[
                    'keyword' => $Ekey
                ]);?> </li>
        <?php } ?>
    </ol>
</nav>
