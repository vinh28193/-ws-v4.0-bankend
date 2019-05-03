<?php

use yii\helpers\Html;
use frontend\widgets\cms\WeshopBlockWidget;
use frontend\widgets\cms\HotDealWidget;
use frontend\widgets\cms\FiveWidget;
use frontend\widgets\cms\FiveImgWidget;
use frontend\widgets\cms\SevenWidget;
use frontend\widgets\cms\SevenImgWidget;

/* @var $this yii\web\View */
/* @var $page common\models\cms\WsPage */
/* @var $data array */

//Toto Page Title here


if (!empty($data)) {
    foreach ($data as $key => $value) {
        $type = $value['block']['type'];
        if ($type === WeshopBlockWidget::LANDING) {
            echo HotDealWidget::widget([
                'block' => $value,
            ]);
        } elseif ($type === WeshopBlockWidget::BLOCK7) {
            echo SevenWidget::widget([
                'block' => $value,
            ]);
        }elseif ($type === WeshopBlockWidget::IMG5){
            echo FiveImgWidget::widget([
                'block' => $value,
            ]);
        }elseif ($type === WeshopBlockWidget::IMG7){
            echo SevenImgWidget::widget([
                'block' => $value,
            ]);
        }elseif ($type === WeshopBlockWidget::BLOCK5){
            foreach ($data as $key2 => $value2){
                $type2 = $value2['block']['type'];
                if($type2 ===  WeshopBlockWidget::BLOCK5){
                    echo FiveWidget::widget([
                            'block' => $value2
                    ]);
                    unset($data[$key2]);
                }
            }
        }
    }
}
?>


<!--<div class="container">-->
<!--    <div class="buy-amazon">-->
<!--        <div class="left">-->
<!--            <div class="title">-->
<!--                <a href="http://amazon.com/"><img src="./img/logo_amz_white.png" alt="Amazon" title="Amazon"></a>-->
<!--            </div>-->
<!--            <div class="list">-->
<!--                <ul>-->
<!--                    <li><a href="#">Flash <span class="sale">Sale</span> <span class="hot-tag">HOT</span></a></li>-->
<!--                    <li><a href="#">Cyber Monday</a></li>-->
<!--                    <li><a href="#">All Brand</a></li>-->
<!--                    <li><a href="#">All Products</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="banner-deal">-->
<!--                <img src="https://static-v3.weshop.com.vn/uploadImages/439874/439874c567b1022586a7da4a26153828.jpg"-->
<!--                     alt="Deal" title="Deal">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="right">-->
<!--            <div class="top">-->
<!--                <ul>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/8dfef7/8dfef7ac44325af35fff2c14967ef320.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/84d762/84d762286fc36036fb090f94f4a4ae58.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/c8556a/c8556a3329d8b2ff61f428d1474ca9d0.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/4f68e8/4f68e8ad42fc7e40562c1fac956732af.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/0d13a1/0d13a10b179548a8f451cc7a58e65105.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/1e0e28/1e0e2819c21cf6d1bb4da2d903dd7e1e.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/d75b69/d75b69353dde1dd81f7ccbae3780e30f.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/eeb58a/eeb58ad1743eb03a70f2028ca103fc5d.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="middle">-->
<!--                <div class="banner-1">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/e49797/e49797a08c5040c0267d36a79b586c48.jpg"-->
<!--                                alt="Banner 1" title="Banner 1"></a>-->
<!--                </div>-->
<!--                <div class="banner-2">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/53e6df/53e6df3a1bf5beda2ecdea299635284b.jpg"-->
<!--                                alt="Banner 2" title="Banner 2"></a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="bottom">-->
<!--                <div class="banner-3">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/eef05c/eef05cab98cdc7e93cbea002150e3ab4.jpg"-->
<!--                                alt="Banner 3" title="Banner 3"></a>-->
<!--                </div>-->
<!--                <div class="banner-4">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/7c7d3e/7c7d3eacbbefa39e685964aa8b50429e.jpg"-->
<!--                                alt="Banner 4" title="Banner 4"></a>-->
<!--                </div>-->
<!--                <div class="banner-5">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/923862/9238624fb75c3aec6c50cca088960c4f.jpg"-->
<!--                                alt="Banner 5" title="Banner 5"></a>-->
<!--                </div>-->
<!--                <div class="banner-6">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/e50a78/e50a78e287636e5ebfbc60ca2f2ec81f.jpg"-->
<!--                                alt="Banner 6" title="Banner 6"></a>-->
<!--                </div>-->
<!--                <div class="banner-7">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/5fe962/5fe96210dcbe385af6a1e5f32ed17dac.jpg"-->
<!--                                alt="Banner 7" title="Banner 7"></a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<div class="container">-->
<!--    <div class="buy-amazon buy-ebay ebay-block">-->
<!--        <div class="left">-->
<!--            <span class="ebay-line"><img src="./img/ebay-line.png"/></span>-->
<!--            <div class="title">-->
<!--                <a href="http://ebay.com/"><img src="./img/logo_ebay_white.png"/></a>-->
<!--            </div>-->
<!--            <div class="list">-->
<!--                <ul>-->
<!--                    <li><a href="#">Cyber Monday</a></li>-->
<!--                    <li><a href="#">All Brand</a></li>-->
<!--                    <li><a href="#">All Brand</a></li>-->
<!--                    <li><a href="#">All Products</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="banner-deal">-->
<!--                <img src="https://static-v3.weshop.com.vn/uploadImages/32836e/32836ec2e8b91ee8021453e2f5e15e06.jpg"-->
<!--                     alt="Banner" title="Banner">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="right">-->
<!--            <div class="top">-->
<!--                <ul>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/8dfef7/8dfef7ac44325af35fff2c14967ef320.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/84d762/84d762286fc36036fb090f94f4a4ae58.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/c8556a/c8556a3329d8b2ff61f428d1474ca9d0.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/4f68e8/4f68e8ad42fc7e40562c1fac956732af.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/0d13a1/0d13a10b179548a8f451cc7a58e65105.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/1e0e28/1e0e2819c21cf6d1bb4da2d903dd7e1e.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/d75b69/d75b69353dde1dd81f7ccbae3780e30f.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/eeb58a/eeb58ad1743eb03a70f2028ca103fc5d.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="bottom">-->
<!--                <div class="banner-1">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/217764/2177642d55bbfc1839701918ce7ab7c8.jpg"-->
<!--                                alt="Banner 1" title="Banner 1"></a>-->
<!--                </div>-->
<!--                <div class="banner-2">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/b8d9b5/b8d9b5c395985474b6f0939614a7eb6b.jpg"-->
<!--                                alt="Banner 2" title="Banner 2"></a>-->
<!--                </div>-->
<!--                <div class="banner-3">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/fc1847/fc1847130866d3d71a46eb3a2d669795.jpg"-->
<!--                                alt="Banner 3" title="Banner 3"></a>-->
<!--                </div>-->
<!--                <div class="banner-4">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/d32706/d327068dcee3d8f0f286df55c6a7cd2d.jpg"-->
<!--                                alt="Banner 4" title="Banner 4"></a>-->
<!--                </div>-->
<!--                <div class="banner-5">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/137c23/137c23ff4d09820075d3aa7a82016d49.jpg"-->
<!--                                alt="Banner 5" title="Banner 5"></a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<div class="container">-->
<!--    <div class="buy-amazon buy-ebay">-->
<!--        <div class="left">-->
<!--            <div class="title">-->
<!--                <a href="http://amazon.com/"><img src="./img/logo_amz_jp.png" alt="Amazon" title="Amazon"></a>-->
<!--            </div>-->
<!--            <div class="list">-->
<!--                <ul>-->
<!--                    <li><a href="#">Flash <span class="sale">Sale</span> <span class="hot-tag">HOT</span></a></li>-->
<!--                    <li><a href="#">Cyber Monday</a></li>-->
<!--                    <li><a href="#">All Brand</a></li>-->
<!--                    <li><a href="#">All Products</a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="banner-deal">-->
<!--                <img src="https://static-v3.weshop.com.vn/uploadImages/843757/8437579c6f0920361cba8eb8b04866ee.jpg"-->
<!--                     alt="Deal" title="Deal">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="right">-->
<!--            <div class="top">-->
<!--                <ul>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/8dfef7/8dfef7ac44325af35fff2c14967ef320.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/84d762/84d762286fc36036fb090f94f4a4ae58.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/c8556a/c8556a3329d8b2ff61f428d1474ca9d0.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/4f68e8/4f68e8ad42fc7e40562c1fac956732af.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/0d13a1/0d13a10b179548a8f451cc7a58e65105.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/1e0e28/1e0e2819c21cf6d1bb4da2d903dd7e1e.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/d75b69/d75b69353dde1dd81f7ccbae3780e30f.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                    <li><a href="#"><img-->
<!--                                    src="https://static-v3.weshop.com.vn/uploadImages/eeb58a/eeb58ad1743eb03a70f2028ca103fc5d.png"-->
<!--                                    alt="Nike" title="Nike"></a></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="bottom">-->
<!--                <div class="banner-1">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/217764/2177642d55bbfc1839701918ce7ab7c8.jpg"-->
<!--                                alt="Banner 1" title="Banner 1"></a>-->
<!--                </div>-->
<!--                <div class="banner-2">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/b8d9b5/b8d9b5c395985474b6f0939614a7eb6b.jpg"-->
<!--                                alt="Banner 2" title="Banner 2"></a>-->
<!--                </div>-->
<!--                <div class="banner-3">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/fc1847/fc1847130866d3d71a46eb3a2d669795.jpg"-->
<!--                                alt="Banner 3" title="Banner 3"></a>-->
<!--                </div>-->
<!--                <div class="banner-4">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/d32706/d327068dcee3d8f0f286df55c6a7cd2d.jpg"-->
<!--                                alt="Banner 4" title="Banner 4"></a>-->
<!--                </div>-->
<!--                <div class="banner-5">-->
<!--                    <a href="#"><img-->
<!--                                src="https://static-v3.weshop.com.vn/uploadImages/137c23/137c23ff4d09820075d3aa7a82016d49.jpg"-->
<!--                                alt="Banner 5" title="Banner 5"></a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
