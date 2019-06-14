<?php

use common\components\StoreManager;
use common\models\cms\WsAliasItem;

/**
 * @var WsAliasItem[] $categories
 * @var StoreManager $storeManager
 */
/*
foreach ($categories as $k => $category) {
    ?>
    <li class="sub-2 <?= $k == 0 ? 'open' : '' ?>">
        <a href="<?= $category->url ?>"><?= $category->name ?></a>
        <div class="sub-menu">
            <div class="ebay-sub-menu">
                <div class="left">
                    <?php foreach ($category->wsCategoryGroups as $wsCategoryGroup) { ?>
                        <div class="title-box">
                            <div class="title"><?= $wsCategoryGroup->name ?></div>
                            <div class="desc"></div>
                        </div>
                        <div class="row">
                            <?php foreach ($wsCategoryGroup->wsParentCategories as $parentCategory) {
                                ?>
                                <div class="col-md-6">
                                    <div class="item">
                                        <div class="title"><?= $parentCategory->name ?></div>
                                        <ul>
                                            <?php foreach ($parentCategory->wsCategories as $wsCategory) { ?>
                                                <li><a href="<?= $wsCategory->url ?>"><?= $wsCategory->name ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="right">
                    <div class="banner-sub">
                        <a href="#"><img src="<?= $category->image ?>" alt="" title=""/></a>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <?php
    if ($k + 1 == count($categories)) {
        ?>
        <li class='sub-2'>
            <div class="see-all">
                <a href="#">Xem toàn bộ danh mục <i class="fas fa-arrow-right"></i></a>
            </div>
        </li>
    <?php
    }
}
*/
?>
  <?php foreach ($categories as $k => $category) {?>
    <li class="li-item-cate">
        <a class="title-cate" href="<?= $category->url ?>">
            <i class="style-i">
<!--                <img class="style-img" src="/images/Bitmap1.png" alt="">-->
            </i><?= $category->name ?> <i class="la la-angle-right float-right mt-2"></i></a>
        <div class="sub-menu-2">
            <div class="ebay-sub-menu ebay ml-1">
                <div class="row">
                    <div class="col-md-6 pl-4">
                        <?php foreach ($category->wsCategoryGroups as $wsCategoryGroup) { ?>
                            <div class="title-box">
                                <div class="title"><?= $wsCategoryGroup->name ?></div>
                                <div class="desc"></div>
                            </div>
                            <div class="row">
                                <?php foreach ($wsCategoryGroup->wsParentCategories as $parentCategory) {
                                    ?>
                                    <div class="col-md-6">
                                        <div class="item">
                                            <div class="title"><?= $parentCategory->name ?></div>
                                            <ul>
                                                <?php foreach ($parentCategory->wsCategories as $wsCategory) { ?>
                                                    <li><a href="<?= $wsCategory->url ?>"><?= $wsCategory->name ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <div class="banner-sub">
                            <a href="#"><img src="<?= $category->image ?>" alt="" title=""/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php } ?>
