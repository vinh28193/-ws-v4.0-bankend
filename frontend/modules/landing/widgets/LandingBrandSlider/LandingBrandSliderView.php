<?php
use common\components\RedisLanguage;
use common\components\UrlComponent;
use common\models\enu\SiteConfig;
use yii\helpers\Url;

?>

<div class="lts-content-all">
    <div class="lts-title">
        <h3>TOP <B> STORE</b></h3>
    </div>
    <div class="lts-content">
        <div class="container">
            <div class="lts-detail">
                <div class="landing-brand-slider on-mobile">
                    <?php if (!empty($images)) {
                        foreach ($images as $key => $value) {
                            ?>
                            <div class="item">
                                <a href="<?php echo $value->link?>" target="_blank"><img
                                        src="<?php echo $value->domain.$value->origin_src?>"
                                        alt="<?php echo $value->name?>" title="<?php echo $value->name?>"/>
                                </a>
                            </div>
                            <?php
                        }
                    } ?>


                </div>
            </div>
        </div>
    </div>
</div>