<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 5/22/2019
 * Time: 8:50 PM
 */

?>

<div class="row">
    <div class="col-lg-12 p-0">
        <div class="modal-body p-0">
            <div class="ng-star-inserted">
                <div class="col-md-12 m-0 col-xl-12 chat">
                    <div class="card m-0 p-0">
                        <div class="card-header msg_head bg-info">
                            <div class="d-flex bd-highlight">
                                <h3 class="text-white">Trao đổi với nhân viên (12345)</h3>
                            </div>
                        </div>
                        <div class="card-body msg_card_body" #scrollMe [scrollTop]="scrollMe.scrollHeight">
                            <div>
                                <div>
                                    <div class="d-flex justify-content-start mb-4">
                                        <div class="img_cont_msg">
                                            <img src="../img/weshop_small_logo.png"
                                                 class="rounded-circle user_img_msg"  width="54px" height="15px">
                                        </div>
                                        <div>
                                            <div class="">
                                                <span class="mr-2">weshop</span>
                                                <p  class="text-darkgray">12345</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                                <div class="input-group">
            <textarea name="" rows="4" class="form-control type_msg"
                      placeholder="Nhập gửi nội dung trao đổi.
Nhấn Shift + Enter để xuống dòng.
Enter để gửi"></textarea>
                                    <div class="input-group-btn">
                                        <button style="height: 97px; border: 1px solid #ced4da; border-radius: 0" class="btn btn-default pl-5 pr-5"><span style="font-weight: 500">Sent</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right mt-2">
        <button class="btn btn-danger btn-sm" data-dismiss="modal" >Cancel</button>
    </div>
</div>
