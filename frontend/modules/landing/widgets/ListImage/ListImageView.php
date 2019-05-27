<?php
use common\components\RedisLanguage;
use common\components\UrlComponent;
use common\models\enu\SiteConfig;
use yii\helpers\Url;

?>


<div class="landing-event">
    <div class="container">
        <div class="item-list">
            <ul>

                <?php if (!empty($images)) {
                    foreach ($images as $key => $val) { ?>
                        <li>
                            <a href="<?= $val['link'] ?>" class="item" target="_blank">
                                <div class="banner">
                                    <img src="<?php echo $val['domain'] . $val['origin_src']; ?>"
                                         alt="<?= $val['name'] ?>"
                                         title="<?= $val['name'] ?>">
                                </div>
                                <div class="text-box">
                                    <span><?= $val['name'] ?></span>
                                    <i class="arrow-ico"></i>
                                </div>
                            </a>
                        </li>
                    <?php }
                } ?>

            </ul>
        </div>
    </div>
</div>