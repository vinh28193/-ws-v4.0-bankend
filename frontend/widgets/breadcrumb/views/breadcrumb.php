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
        <li class="breadcrumb-item"><a href="/<?= $portal?>.html">Shop Amazon</a></li>
        <li class="breadcrumb-item active">Tìm kiếm từ khóa <?= Yii::$app->request->get('keyword','') ?></li>
    </ol>
</nav>