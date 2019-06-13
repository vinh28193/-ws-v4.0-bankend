<?php

use common\components\StoreManager;
use common\models\cms\WsAliasItem;

/**
 * @var WsAliasItem[] $categories
 * @var StoreManager $storeManager
 * @var string $logoMobile
 * @var string $type
 */
?>
  <?php foreach ($categories as $k => $category) {?>
    <li>
        <a href="<?= $category->url ?>">
            <i class="style-i">
<!--                <img class="style-img" src="/images/Bitmap1.png" alt="">-->
            </i><?= $category->name ?> </a>
        <i class="la la-angle-right float-right mt-2" onclick="showTab('sub-tab-cate-<?= $type?>-<?= $k ?>')"></i>
        <div class="sub-cate-2" id="sub-tab-cate-<?= $type?>-<?= $k ?>" style="background-color: #f6f6f6;">
            <div class="title-mb-menu" onclick="hideTab('sub-tab-cate-<?= $type?>-<?= $k ?>')">
                <i class="la la-close"></i>
                <span>Danh mục sản phẩm</span>
            </div>
            <div class="title-submenu" style="background-color: #ffffff;">
                <a style="display: block"
                   aria-expanded="true">
                    <img src="<?= $logoMobile ? $logoMobile : "/images/logo/weshop-01.png" ?>" style="height: 22px">
                </a>
            </div>
            <div class="content-menu-cate-sub-2">
                <div class="title-submenu">
                    <i class="la la-angle-left float-left" onclick="hideTab('sub-tab-cate-<?= $type?>-<?= $k ?>')"></i>
                    <a href="<?= $category->url ?>">
                        <?= $category->name ?>
                    </a>
                </div>
                <div class="content-menu-cate-sub-2-item">
                    <?php foreach ($category->wsCategoryGroups as $wsCategoryGroup) {
                        foreach ($wsCategoryGroup->wsParentCategories as $parentCategory) {
                            ?>
                            <div class="title"><?= $parentCategory->name ?></div>
                            <ul>
                                <?php foreach ($parentCategory->wsCategories as $wsCategory) { ?>
                                    <li><a href="<?= $wsCategory->url ?>"><?= $wsCategory->name ?></a></li>
                                <?php } ?>
                            </ul>
                        <?php }
                    } ?>
                    <!--<div>
                        <div class="banner-sub">
                            <a href="<?/*= $category->url */?>"><img src="<?/*= $category->image */?>" alt="" title=""/></a>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </li>
<?php } ?>
