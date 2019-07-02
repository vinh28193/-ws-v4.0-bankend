<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $code string */
/* @var $token string */
/* @var $billingNm string */
/* @var $transTm string */
/* @var $transDt string */
/* @var $bankVacctNo string */
/* @var $vacctValidDt string */
/* @var $vacctValidTm string */
/* @var $bankCd string */
/* @var $currency string */
/* @var $amount string */

$storeManager = Yii::$app->storeManager;

?>
<div class="container">
    <?php
    $year = substr($transDt, 0, 4);
    $month = substr($transDt, 4, 2);
    $day = substr($transDt, 6, 2);
    $hour = substr($transTm, 0, 2);
    $minute = substr($transTm, 2, 2);
    $strDate = "{$day}-{$month}-{$year} {$hour}:{$minute}";
    $date = strtotime($strDate);
    $timestamp = strtotime("+1 day", $date);
    $outdate = date('d-m-Y / H:i', $timestamp);
    $bankName = '';

    $methodName = [];
    if ($bankCd == 'CENA') {
        $bankName = 'Central Asia (BCA)';
        $methodName[] = 'ATM Mandiri';
        $methodName[] = 'Internet Banking Mandiri';
        $methodName[] = 'Mobile Banking Mandiri';
    } else if ($bankCd == 'BNIN') {
        $bankName = 'Negara Indonesia (BNI)';
        $methodName[] = 'ATM BNI';
        $methodName[] = 'Internet Banking BNI';
        $methodName[] = 'Mobile Banking BNI';
        $methodName[] = 'SMS Banking BNI';
    } else if ($bankCd == 'BMRI') {
        $bankName = 'Mandiri';
        $methodName[] = 'ATM Mandiri';
        $methodName[] = 'Internet Banking Mandiri';
        $methodName[] = 'Mobile Banking Mandiri';
    } else if ($bankCd == 'BBBA') {
        $bankName = 'Permata';
        $methodName[] = 'ATM Bank Permata';
        $methodName[] = 'Internet Banking Bank Permata';
        $methodName[] = 'Mobile Banking Bank Permata';
    } else if ($bankCd == 'IBBK') {
        $bankName = 'Maybank Indonesia (BII)';
        $methodName[] = 'ATM';
        $methodName[] = 'Internet Banking';
        $methodName[] = 'SMS Banking Maybank Indonesia (BII)';
    } else if ($bankCd == 'HNBN') {
        $bankName = 'ATM Bersama Anda';
        $methodName[] = 'ATM';
        $methodName[] = 'Internet Banking';
        $methodName[] = 'SMS Banking ATM Bersama';
    }
    ?>
    <div class="cbn-content">
        <div class="cbn-title">
            <h3>Informasi Virtual Account Bank <?= $bankName; ?></h3>
        </div>
        <div class="cbn-detail">
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">No. Virtual Account :</label>
                    <div class="col-sm-6">
                        <p><?= $bankVacctNo; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Bank :</label>
                    <div class="col-sm-6">
                        <p>Bank <?= $bankName; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Nominal :</label>
                    <div class="col-sm-6">
                        <p>Rp. <?= $storeManager->showMoney($amount); ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Nama Penerima :</label>
                    <div class="col-sm-6">
                        <p><?= $billingNm; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Nomor Order :</label>
                    <div class="col-sm-6">
                        <p><?= $code; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Deskripsi :</label>
                    <div class="col-sm-6">
                        <p><?= $description; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-6 control-label">Berlaku Hingga :</label>
                    <div class="col-sm-6">
                        <p><?= $outdate; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <p>Pastikan Anda memasukkan nomor Virtual Account yang <b>BENAR</b> saat melakukan pembayaran.
                            Anda dapat transfer via <?php $i = 0;
                            if (!empty($methodName)) foreach ($methodName as $method) { ?><?php if ($i == 0) {
                                echo $method;
                            } else {
                                echo ' / ' . $method;
                            } ?><?php $i++;
                            } ?>.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if ($bankCd == 'CENA') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM Bank Central Asia (BCA)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Transaksi Lainnya</li>
                    <li>2. Pilih Transfer</li>
                    <li>3. Pilih ke Rekening BCA Virtual Account</li>
                    <li>4. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> </li>
                    <li>5. Pilih Benar</li>
                    <li>6. Pilih Ya</li>
                    <li>7. Ambil bukti pembayaran Anda</li>
                    <li>8. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking Bank Central Asia (BCA)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Transaksi Dana</li>
                    <li>3. Pilih Transfer ke BCA Virtual Account</li>
                    <li>4. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> </li>
                    <li>5. Klik Lanjutkan</li>
                    <li>6. Input Respon KeyBCA Appli 1</li>
                    <li>7. Klik Kirim</li>
                    <li>8. Bukti bayar ditampilkan</li>
                    <li>9. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking Bank Central Asia (BCA)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Mobile Banking</li>
                    <li>2. Pilih m-Transfer</li>
                    <li>3. Pilih BCA Virtual Account</li>
                    <li>4. Ketik/Pilih Transferpay sebagai Penyedia Jasa</li>
                    <li>5. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?></li>
                    <li>6. Klik Send</li>
                    <li>7. Informasi Virtual Account akan ditampilkan</li>
                    <li>8. Klik OK</li>
                    <li>9. Input PIN Mobile Banking</li>
                    <li>10. Bukti bayar ditampilkan</li>
                    <li>11. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'BNIN') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM BNI</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Menu Lain</li>
                    <li>2. Pilih Transfer</li>
                    <li>3. Pilih Sumber Rekening</li>
                    <li>4. Pilih Ke Rekening BNI</li>
                    <li>5. Pilih tipe akun Anda, misal Rekening Tabungan</li>
                    <li>6. Ketik <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda</li>
                    <li>7. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?></li>
                    <li>8. Pilih Benar</li>
                    <li>9. Pilih Ya</li>
                    <li>10. Ambil bukti pembayaran Anda</li>
                    <li>11. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking BNI</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Info dan Administrasi</li>
                    <li>3. Pilih Atur Rekening Tujuan</li>
                    <li>4. Ketik Nomor Pesanan <?= $code; ?> sebagai Nama Singkat lalu Lanjutkan</li>
                    <li>5. Ketik <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda</li>
                    <li>6. Klik Lanjutkan</li>
                    <li>7. Masukkan Token lalu Process</li>
                    <li>8. Pilih Transfer rekening antar BNI</li>
                    <li>9. Pilih Nomor Order <?= $code; ?></li>
                    <li>10. Ketik Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?></li>
                    <li>11. Bukti bayar ditampilkan</li>
                    <li>12. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking BNI</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Transfer</li>
                    <li>2. Pilih Within Bank</li>
                    <li>3. Pilih Adhoc Beneficiary</li>
                    <li>4. Ketik Nomor Pesanan <?= $code; ?> sebagai Nickname</li>
                    <li>5. Ketik Nomor Virtual Account <?= $bankVacctNo; ?></li>
                    <li>6. Ketik email Anda</li>
                    <li>7. Hilangkan centang Add to Favourite list lalu klik Continue</li>
                    <li>8. Pilih Continue dan input Password Anda</li>
                    <li>9. Pilih Continue</li>
                    <li>10. Bukti bayar ditampilkan</li>
                    <li>11. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui SMS Banking BNI</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Transfer</li>
                    <li>2. Pilih Trf Rekening BNI</li>
                    <li>3. Masukkan Nomor Virtual Account <?= $bankVacctNo; ?></li>
                    <li>4. Masukkan jumlah tagihan Anda yaitu 358356 pada field Amount</li>
                    <li>5. Pilih Proses</li>
                    <li>6. Pada Pop Up message, Pilih Setuju</li>
                    <li>7. Anda akan mendapatkan SMS konfirmasi</li>
                    <li>8. Masukkan 2 angka dari PIN SMS Banking sesuai petunjuk</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'BMRI') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM Bank Mandiri</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Bayar/Beli</li>
                    <li>2. Pilih Lainnya</li>
                    <li>3. Pilih MultiPayment</li>
                    <li>4. Ketik <?= substr($bankVacctNo, 0, 5); ?> sebagai Kode Institusi</li>
                    <li>5. Ketik <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda</li>
                    <li>6. Pilih Benar</li>
                    <li>7. Pilih Ya</li>
                    <li>8. Pilih Ya</li>
                    <li>9. Ambil bukti pembayaran Anda</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking Bank Mandiri</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Bayar</li>
                    <li>3. Pilih Multi Payment</li>
                    <li>4. Ketik/Pilih Transferpay sebagai Penyedia Jasa</li>
                    <li>5. Ketik <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda</li>
                    <li>6. Ceklis IDR</li>
                    <li>7. Klik Lanjutkan</li>
                    <li>8. Bukti bayar ditampilkan</li>
                    <li>9. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking Bank Mandiri</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Mobile Banking</li>
                    <li>2. Pilih Bayar</li>
                    <li>3. Pilih Lainnya</li>
                    <li>4. Ketik/Pilih Transferpay sebagai Penyedia Jasa</li>
                    <li>5. Ketik <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda</li>
                    <li>6. Pilih Lanjut</li>
                    <li>7. Ketik OTP dan IPN</li>
                    <li>8. Pilih OK</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'BBBA') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Transaksi Lainnya</li>
                    <li>2. Pilih Pembayaran</li>
                    <li>3. Pilih Pembayaran Lain-lain</li>
                    <li>4. Pilih Virtual Account</li>
                    <li>5. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda
                    </li>
                    <li>6. Pilih Benar</li>
                    <li>7. Pilih Ya</li>
                    <li>8. Ambil bukti pembayaran Anda</li>
                    <li>9. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Pembayaran Tagihan</li>
                    <li>3. Pilih Virtual Account</li>
                    <li>4. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda
                    </li>
                    <li>5. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?></li>
                    <li>6. Klik Kirim</li>
                    <li>7. Input Token</li>
                    <li>8. Klik Kirim</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Mobile Banking</li>
                    <li>2. Pilih Pembayaran Tagihan</li>
                    <li>3. Pilih Virtual Account</li>
                    <li>4. Input Nomor Virtual Account <?= $bankVacctNo; ?> </li>
                    <li>5. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?> pada field
                        Amount
                    </li>
                    <li>6. Klik Kirim</li>
                    <li>7. Input Token</li>
                    <li>8. Klik Kirim</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'IBBK') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM Maybank Indonesia (BII)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Pembayaran/Top Up Pulsa</li>
                    <li>2. Pilih Virtual Account</li>
                    <li>3. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?></li>
                    <li>4. Pilih Benar</li>
                    <li>5. Pilih Ya</li>
                    <li>6. Ambil bukti pembayaran Anda</li>
                    <li>7. Selesai</li>
                </ul>
            </div>
        </div>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking Maybank Indonesia (BII)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Rekening dan Transaksi</li>
                    <li>3. Pilih Maybank Virtual Account</li>
                    <li>4. Pilih Sumber tabungan</li>
                    <li>5. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?></li>
                    <li>6. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?></li>
                    <li>7. Klik Submit</li>
                    <li>8. InputSMS Token</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui SMS Banking Maybank Indonesia (BII)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. SMS ke 69811</li>
                    <li>2. Ketik TRANSFER< NomorVirtualAccount >< Nominal ></li>
                    <li>3. Contoh: TRANSFER <?= $bankVacctNo; ?> <?= $storeManager->showMoney($amount); ?></li>
                    <li>4. Kirim SMS</li>
                    <li>5. Anda akan mendapatkan balasan Transfer dr rek < nomor rekening anda > ke rek < Nomor Virtual
                        Account > sebesar Rp. <?= $storeManager->showMoney($amount); ?> Ketik < karakter acak >
                    </li>
                    <li>6. Balas SMS tersebut, ketik < karakter acak ></li>
                    <li>7. Kirim SMS</li>
                    <li>8. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'BBBA') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Transaksi Lainnya</li>
                    <li>2. Pilih Pembayaran</li>
                    <li>3. Pilih Pembayaran Lain-lain</li>
                    <li>4. Pilih Virtual Account</li>
                    <li>5. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda
                    </li>
                    <li>6. Pilih Benar</li>
                    <li>7. Pilih Ya</li>
                    <li>8. Ambil bukti pembayaran Anda</li>
                    <li>9. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Internet Banking Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Internet Banking</li>
                    <li>2. Pilih Pembayaran Tagihan</li>
                    <li>3. Pilih Virtual Account</li>
                    <li>4. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?> sebagai nomor Virtual Account Anda
                    </li>
                    <li>5. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?></li>
                    <li>6. Klik Kirim</li>
                    <li>7. Input Token</li>
                    <li>8. Klik Kirim</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking Bank Permata</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Login Mobile Banking</li>
                    <li>2. Pilih Pembayaran Tagihan</li>
                    <li>3. Pilih Virtual Account</li>
                    <li>4. Input Nomor Virtual Account <?= $bankVacctNo; ?> </li>
                    <li>5. Input Nominal pesanan Anda yaitu Rp. <?= $storeManager->showMoney($amount); ?> pada field
                        Amount
                    </li>
                    <li>6. Klik Kirim</li>
                    <li>7. Input Token</li>
                    <li>8. Klik Kirim</li>
                    <li>9. Bukti bayar ditampilkan</li>
                    <li>10. Selesai</li>
                </ul>
            </div>
        </div>
    <?php } else if ($bankCd == 'HNBN') { ?>
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui ATM</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Pilih Menu Transfer</li>
                    <li>2. Pilih Bank Lainnya</li>
                    <li>3. Input Kode Bank 484</li>
                    <li>4. Input Nomor Virtual Account, yaitu <?= $bankVacctNo; ?></li>
                    <li>5. Input Nominal pesanan Anda yaitu <?= $storeManager->showMoney($amount); ?></li>
                    <li>6. Pilih Benar</li>
                    <li>7. Pilih Ya</li>
                    <li>8. Ambil bukti pembayaran Anda</li>
                    <li>9. Selesai</li>
                </ul>
            </div>
        </div>

        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui Mobile Banking, SMS Banking, Internet Banking</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. Lakukan seperti Anda mentransfer ke Bank Lain pada umumnya</li>
                    <li>2. Input 484 sebagai Kode Bank</li>
                    <li>3. Input Nomor Virtual Account sebagai Nomor Rekening, yaitu <?= $bankVacctNo; ?></li>
                    <li>4. Input Nominal yang ditagihkan sebagai Nominal Transfer
                        yaitu <?= $storeManager->showMoney($amount); ?> </li>
                    <li>5. Selesai</li>
                </ul>
            </div>
        </div>
        <!--
        <div class="cbn-content">
            <div class="cbn-title">
                <h3>Panduan Pembayaran Melalui SMS Banking Maybank Indonesia (BII)</h3>
            </div>
            <div class="cbn-detail">
                <ul>
                    <li>1. SMS ke 69811</li>
                    <li>2. Ketik TRANSFER< NomorVirtualAccount >< Nominal ></li>
                    <li>3. Contoh: TRANSFER <?= $bankVacctNo; ?> <?= $storeManager->showMoney($amount); ?></li>
                    <li>4. Kirim SMS </li>
                    <li>5. Anda akan mendapatkan balasan Transfer dr rek < nomor rekening anda > ke rek < Nomor Virtual Account > sebesar Rp. <?= $storeManager->showMoney($amount); ?> Ketik < karakter acak ></li>
                    <li>6. Balas SMS tersebut, ketik < karakter acak ></li>
                    <li>7. Kirim SMS</li>
                    <li>8. Selesai</li>
                </ul>
            </div>
        </div>
-->
    <?php } ?>
    <a href="/"
       class="btn btn-primary">Finish</a>
</div>

<div class="modal fade" id="bcardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body md-body">
                <div class="md-icon">
                    <i class="fa fa-check" aria-hidden="true"></i>
                </div>
                <div class="md-text">
                    <h3>Selangkah Lagi!</h3>
                    <p>Silahkan transfer dana ke Virtual Account dan pembelian anda akan otomatis terkonfirmasi</p>
                </div>
                <div class="md-button">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>