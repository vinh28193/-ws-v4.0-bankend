<?php
/**
 *
 */

use frontend\modules\account\views\widgets\HeaderContentWidget;

$this->title = "Tạo yêu cầu rút tiền";
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => ['Rút tiền' => '/my-weshop/wallet/withdraw.html']]);
?>

<div class="be-box">
    <div class="be-top">
        <div class="title">Thông tin số dư tài khoản</div>
    </div>
    <div class="be-body be-withdraw">
        <div class="be-table">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Số dư tài khoản</th>
                    <th scope="col">Số dư đóng băng</th>
                    <th scope="col">Số dư khả dụng</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>50.800.000đ</td>
                    <td>20.000đ</td>
                    <td>50.800.000đ</td>
                </tr>
                </tbody>
            </table>
        </div>
        <ul class="withdraw-step">
            <li class="done">
                <div class="step">1</div>
                <p>Tạo Yêu cầu rút</p>
            </li>
            <li class="active">
                <div class="step">2</div>
                <p>Xác nhận yêu cầu rút</p>
            </li>
            <li>
                <div class="step">3</div>
                <p>Yêu cầu rút thành công</p>
            </li>
        </ul>
        <div class="title-2">Qúy khách vui lòng chọn phương thức rút tiền</div>
        <div class="withdraw-box">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#withdraw-1" role="tab" aria-selected="true"><span>Rút tiền về ví Weshop</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#withdraw-2" role="tab" aria-selected="false"><span>Rút tiền về tài khoản ngân hàng</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="withdraw-1" role="tabpanel">
                    <div class="form-group">
                        <div class="label">Email tài khoản Weshop</div>
                        <input type="email" class="form-control" placeholder="Email tài khoản Weshop">
                    </div>
                    <div class="form-group">
                        <div class="label">Email tài khoản Weshop</div>
                        <b>nhientt@gmail.com</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Số tiền cần rút</div>
                        <input type="text" class="form-control" placeholder="Nhập số tiền cần rút">
                    </div>
                    <div class="form-group">
                        <div class="label">Số tiền cần rút</div>
                        <b>900.000 đ</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Phí rút tiền</div>
                        <b>10.000 đ</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Tổng số tiền rút</div>
                        <b class="text-orange">828.700 đ</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Xác nhận bằng mất khẩu</div>
                        <input type="password" class="form-control">
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-submit">Xác nhận</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="withdraw-2" role="tabpanel">
                    <div class="form-group">
                        <div class="label">Tên ngân hàng</div>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="choose-bank" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Chọn ngân hàng</button>
                            <div class="dropdown-menu" aria-labelledby="choose-bank">
                                <input type="text" class="form-control" placeholder="Tìm tên ngân hàng">
                                <a class="dropdown-item" href="#">Viettin Bank</a>
                                <a class="dropdown-item" href="#">Techcombank</a>
                                <a class="dropdown-item" href="#">Vietcombank</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">Số tài khoản</div>
                        <input type="email" class="form-control" placeholder="Nhập số tài khoản">
                    </div>
                    <div class="form-group">
                        <div class="label">Chủ tài khoản</div>
                        <input type="text" class="form-control" placeholder="Nhập tên chủ tài khoản">
                    </div>
                    <div class="form-group">
                        <div class="label">Số tiền cần rút</div>
                        <input type="text" class="form-control" placeholder="Nhập số tiền cần rút">
                    </div>
                    <div class="form-group">
                        <div class="label">Phí rút tiền</div>
                        <b>10.000 đ</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Tổng số tiền rút</div>
                        <b class="text-orange">828.700 đ</b>
                    </div>
                    <div class="form-group">
                        <div class="label">Xác nhận bằng mất khẩu</div>
                        <input type="password" class="form-control">
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-submit">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="be-notice">
            <div class="notice-title">Lưu ý:</div>
            - Tổng số tiền rút phải lớn hơn hoặc bằng 100.000đ và nhỏ hơn hoặc bằng Số dư khả dụng.<br/>
            - Phí rút tiền là: 3.000đ + 1%. Phí không vượt quá 10.000đ.<br/>
            - Quý khách lưu ý điền đúng thông tin tài khoản. Nếu quý khách điền sai thông tin mà Weshop đã thực hiện lệnh chuyển tiền thì quý khách sẽ phải chịu phí chuyển tiền của ngân hàng.
        </div>
    </div>
</div>
