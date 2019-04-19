<?php
use yii\helpers\Html;
use frontend\assets\SearchAsset;

/* @var $this yii\web\View */

SearchAsset::register($this);

?>
<div class="search-2-content">
    <div class="row">
        <div class="col-md-3">
            <div class="filter-content">
                <div class="filter-box category">
                    <div class="title">Danh mục</div>
                    <ul id="sub-menu-collapse">
                        <li class="accordion">
                            <a href="#">Quần áo, Giày dép & Trang sức</a>
                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-1" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-down"></i></a>
                            <div id="sub-1" class="collapse" aria-labelledby="headingOne" data-parent="#sub-menu-collapse">
                                <ul>
                                    <li><a href="#">Danh mục 1</a></li>
                                    <li><a href="#">Danh mục 2</a></li>
                                    <li><a href="#">Danh mục 3</a></li>
                                    <li><a href="#">Danh mục 4</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="accordion">
                            <a href="#">Nữ giới</a>
                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-2" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-down"></i></a>
                            <div id="sub-2" class="collapse" aria-labelledby="headingOne" data-parent="#sub-menu-collapse">
                                <ul>
                                    <li><a href="#">Danh mục 1</a></li>
                                    <li><a href="#">Danh mục 2</a></li>
                                    <li><a href="#">Danh mục 3</a></li>
                                    <li><a href="#">Danh mục 4</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="accordion">
                            <a href="#">Đồng hồ đeo tay</a>
                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-3" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-down"></i></a>
                            <div id="sub-3" class="collapse" aria-labelledby="headingOne" data-parent="#sub-menu-collapse">
                                <ul>
                                    <li><a href="#">Danh mục 1</a></li>
                                    <li><a href="#">Danh mục 2</a></li>
                                    <li><a href="#">Danh mục 3</a></li>
                                    <li><a href="#">Danh mục 4</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="accordion">
                            <a href="#">Tiểu thuyết & Khác</a>
                            <a class="dropdown-collapse collapsed" data-toggle="collapse" data-target="#sub-4" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-chevron-down"></i></a>
                            <div id="sub-4" class="collapse" aria-labelledby="headingOne" data-parent="#sub-menu-collapse">
                                <ul>
                                    <li><a href="#">Danh mục 1</a></li>
                                    <li><a href="#">Danh mục 2</a></li>
                                    <li><a href="#">Danh mục 3</a></li>
                                    <li><a href="#">Danh mục 4</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Thời trang Amazon</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="amz-fashion">
                                <label class="form-check-label" for="amz-fashion">Thương hiệu hàng đầu</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Thương hiệu</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand1">
                                <label class="form-check-label" for="brand1">Bulova</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand2">
                                <label class="form-check-label" for="brand2">Henry Jay</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand3">
                                <label class="form-check-label" for="brand3">Harley- Davidson</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand4">
                                <label class="form-check-label" for="brand4">Accutron II</label>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="more">Xem thêm <i class="fas fa-angle-double-right"></i></a>
                </div>
                <div class="filter-box">
                    <div class="title">Điểm đến mới</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="30day">
                                <label class="form-check-label" for="30day">30 ngày trước</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="90day">
                                <label class="form-check-label" for="90day">90 ngày qua</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Add-on Item</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addon">
                                <label class="form-check-label" for="addon">Exclude Add-on</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Vận chuyển quốc tế</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ship">
                                <label class="form-check-label" for="ship">International Shipping Eligible</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Amazon Global Store</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="amz-store">
                                <label class="form-check-label" for="amz-store">Amazon Global Store</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="filter-box">
                    <div class="title">Nhà cung cấp</div>
                    <ul>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="shop1">
                                <label class="form-check-label" for="shop1">Amazon.com</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="shop2">
                                <label class="form-check-label" for="shop2">Đồng hồ chính hãng</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="shop3">
                                <label class="form-check-label" for="shop3">Imperial123</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="shop4">
                                <label class="form-check-label" for="shop4">Xu hướng thời gian của Tic</label>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="more">Xem thêm <i class="fas fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="search-content search-2">
                <div class="title-box">
                    <div class="left">
                        <div class="text">Tìm kiếm “Bulova” từ</div>
                        <img src="/img/logo_ebay.png" alt=""/>
                        <span>Hiển thị 1-48 của 1.000 kết quả.</span>
                    </div>
                    <div class="right">
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sắp xếp theo</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" type="button">Action</button>
                                <button class="dropdown-item" type="button">Another action</button>
                                <button class="dropdown-item" type="button">Something else here</button>
                            </div>
                        </div>
                        <ul class="control-page">
                            <li><a href="#" class="control prev"></a></li>
                            <li><a href="#" class="control next"></a></li>
                        </ul>
                    </div>
                </div>

                <div class="product-list row">
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://static-v3.weshop.com.vn/upload/x/2/z/6/c/1/c/o/6/b/bulova-men-s-96c125-quartz-b-36709.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                    <span class="sale-tag">30% OFF</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51O7o0kQcZL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                    <span class="sale-tag">30% OFF</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51fHqtA0juL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                    <span class="sale-tag">30% OFF</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51fHqtA0juL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51fHqtA0juL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <a href="#" class="item">
                            <div class="thumb">
                                <img src="https://images-fe.ssl-images-amazon.com/images/I/51I5IuT1BmL._AC_US200_.jpg" alt="" title=""/>
                            </div>
                            <div class="info">
                                <div class="rate text-orange">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                    <span>(87)</span>
                                </div>
                                <div class="name">Bulova Men's 96B104 Stainless Steel ANF Dress Watch</div>
                                <div class="price">
                                    <strong>20.430.000</strong>
                                    <span>6.800.000</span>
                                </div>
                                <div class="price-detail">*Xem giá trọn gói về Việt Nam</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <nav aria-label="...">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true"></a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><span class="more">...</span></li>
                    <li class="page-item"><a class="page-link last" href="#">20</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

