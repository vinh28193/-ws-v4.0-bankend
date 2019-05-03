<?php

/* @var $this yii\web\View */
/* @var $wsCategoryGroups array */
/* @var $wsAliasItem array */

?>


<div class="left">
    <div class="title-box">
        <div class="title"><?= $wsAliasItem['name']; ?></div>
        <div class="desc"></div>
    </div>
    <div class="row">
        <?php if ($wsCategoryGroups != null) foreach ($wsCategoryGroups['wsParentCategories'] as $category): ?>
            <div class="col-md-6">
                <div class="item">
                    <div class="title"><?= $category['local_name']; ?></div>
                    <ul>
                        <?php foreach ($category['wsCategories'] as $subCate): ?>
                            <li>
                                <a href="<?= $subCate['url'] ?>"><?= $subCate['local_name'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="right">
    <div class="banner-sub">
        <a href="<?= $wsAliasItem['url']; ?>">
            <img src="<?= $wsAliasItem['image']; ?>" alt="<?= $wsAliasItem['name']; ?>" title="">
        </a>
    </div>
</div>
