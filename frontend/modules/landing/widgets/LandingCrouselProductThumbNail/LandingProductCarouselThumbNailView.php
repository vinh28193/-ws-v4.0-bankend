<div class="deal-box fashion">
    <div class="title">
        <div class="left">
            <span><?php $block['name']; ?></span>
        </div>
        <div class="right">
            <span class="btn prev"><i
                    class="fa fa-long-arrow-left"></i> Prev</span>
            <span class="btn next">Sau <i
                    class="fa fa-long-arrow-right"></i></span>
        </div>
    </div>
    <div class="content">
        <div class="banner">
            <a href="#">
                <img src="http://static.weshop.com.vn/upload/h/e/q/2/u/e/0/a/2/3/1349.jpg"
                     alt=""
                     title=""/>
            </a>
        </div>
        <div class="product-box">
            <div id="ld-deal-4" class="owl-carousel owl-theme">
                <?php if (!empty($groupProduct)) {
                    foreach ($groupProduct as $key_group => $group) {
                        ?>
                        <div class="item">
                            <?php if (!empty($group)) {
                                foreach ($group as $key => $value) {
                                    ?>
                                    <a href="/amazon/item/playskool-heroes-transformers-rescue-bots-medix-the-doc-bot-action-figure-B00P2SNIUE.html" class="pd-2">
                                        <div class="thumb">
                                            <span>
                                                <img src="<?= $value['image'] ?>"
                                                     alt="Playskool Heroes Transformers Rescue Bots Medix The Doc-Bot Action Figure"
                                                     title="Playskool Heroes Transformers Rescue Bots Medix The Doc-Bot Action Figure"/>
                                            </span>
                                        </div>
                                        <div class="info">
                                            <span class="name">Playskool Heroes Transformers Rescue Bots Medix The Doc-Bot Action Figure</span>
                                            <span class="price">351.000 VNĐ</span>
                                        </div>
                                        <span class="tag-time"></span>
                                    </a>
                                    <?php
                                }
                            } ?>
                        </div>
                        <?php
                    }
                } ?>
            </div>
        </div>
    </div>