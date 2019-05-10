<?php


?>

<div class="col-md-8">
    <div class="title">Thông tin mua hàng</div>
    <div class="payment-box">
        <form class="payment-form">
            <div class="form-group">
                <i class="icon user"></i>
                <input type="text" class="form-control" placeholder="Họ và tên" value="Trần Thị Nhiên">
            </div>
            <div class="form-group">
                <i class="icon phone"></i>
                <input type="tel" class="form-control" placeholder="Số điên thoại" value="0166 375 1796">
            </div>
            <div class="form-group">
                <i class="icon email"></i>
                <input type="email" class="form-control" placeholder="Email" value="nhien@gmail.com">
            </div>
            <div class="form-group dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="select-city" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon globe"></i> Hà Nội
                </button>
                <div class="dropdown-menu" aria-labelledby="select-city">
                    <input type="text" class="form-control" placeholder="Nhập tên tỉnh/thành phố">
                    <a class="dropdown-item" href="#">Chọn Tỉnh/ Thành Phố</a>
                    <a class="dropdown-item" href="#">Hà Nội</a>
                    <a class="dropdown-item" href="#">TP. Hồ Chí Minh</a>
                    <a class="dropdown-item" href="#">Đà Nẵng</a>
                </div>
            </div>
            <div class="form-group dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="select-district" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon city"></i> Hà Đông
                </button>
                <div class="dropdown-menu" aria-labelledby="select-district">
                    <input type="text" class="form-control" placeholder="Nhập tên quận/huyện">
                    <a class="dropdown-item" href="#">Chọn Quận/ Huyện</a>
                    <a class="dropdown-item" href="#">Quận Hai Bà Trưng</a>
                    <a class="dropdown-item" href="#">Quận Hoàn Kiếm</a>
                    <a class="dropdown-item" href="#">Quận Cầu Giấy</a>
                </div>
            </div>
            <div class="form-group">
                <i class="icon mapmaker"></i>
                <input type="text" class="form-control" placeholder="Địa chỉ (số nhà/ ngách/ tên đường)" value="10 Đại An - Văn Quán">
            </div>
            <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Ghi chú thêm ( không bắt buộc)"></textarea>
            </div>
            <div class="check-info">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Lưu thông tin địa chỉ</label>
                </div>
            </div>
            <a href="#" class="other-receiver">Thông tin người nhận hàng khác người đặt hàng >></a>
            <button type="submit" class="btn btn-payment btn-block">Chọn hình thức thanh toán</button>
        </form>
    </div>
</div>
<div class="col-md-4">
    <div class="title">Đơn hàng <span>(2 sản phẩm)</span> <a href="#" class="far fa-edit"></a></div>
    <div class="payment-box order">
        <div class="top">
            <ul class="order-list">
                <li>
                    <div class="thumb">
                        <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt=""/>
                    </div>
                    <div class="info">
                        <div class="left">
                            <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink Gold-Tone 30mm Watch</a>
                            <p>Bán bởi: <a href="#">Multiple supplier.</a></p>
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="right">
                            <ol class="price">
                                <li>5.800.000 <i class="currency">đ</i></li>
                                <li>x1</li>
                                <li>5.800.000 <i class="currency">đ</i></li>
                            </ol>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="thumb">
                        <img src="https://images-na.ssl-images-amazon.com/images/I/51aLZ8NqnaL.jpg" alt=""/>
                    </div>
                    <div class="info">
                        <div class="left">
                            <a href="#" class="name">Citizen Eco-Drive Women's GA10580-59Q Axiom Diamond Pink Gold-Tone 30mm Watch</a>
                            <p>Bán bởi: <a href="#">Multiple supplier.</a></p>
                            <div class="rate">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="right">
                            <ol class="price">
                                <li>5.800.000 <i class="currency">đ</i></li>
                                <li>x2</li>
                                <li>11.600.000 <i class="currency">đ</i></li>
                            </ol>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="coupon">
                <label>Mã giảm giá:</label>
                <div class="input-group">
                    <input type="text" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>
        <ul class="billing">
            <li>
                <div class="left">Tổng đơn hàng:</div>
                <div class="right">11.600.000 <i class="currency">đ</i></div>
            </li>
            <li>
                <div class="left">Khuyến mãi giảm giá:</div>
                <div class="right text-blue">-100.000 <i class="currency">đ</i></div>
            </li>
            <li>
                <div class="left">Tổng tiền thanh toán:</div>
                <div class="right">17.400.000 <i class="currency">đ</i></div>
            </li>
        </ul>
    </div>
</div>
