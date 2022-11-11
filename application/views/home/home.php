<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-12" id="demo">
                <div class="col-12 row mb-2 justify-content-center">
                    <h6 class="text-center">Road to MUAMMAR </h6>
                </div>
                <div class="col-12 row justify-content-center">
                    <div class="box-timer text-center mr-3">
                        <h2 class="mb-0 text-success" id="day">-</h2>
                        <span>Hari</span>
                    </div>
                    <div class="box-timer text-center mr-3">
                        <h2 class="mb-0 text-success" id="hour">-</h2>
                        <span>Jam</span>
                    </div>
                    <div class="box-timer text-center mr-3">
                        <h2 class="mb-0 text-success" id="minute">-</h2>
                        <span>Menit</span>
                    </div>
                    <div class="box-timer text-center">
                        <h2 class="mb-0 text-success" id="second">-</h2>
                        <span>Detik</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Seluruh Peserta</span>
                        <span class="info-box-number">
                            12 <span class="font-weight-normal">Orang</span>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fa fa-male"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Putra</span>
                        <span class="info-box-number">
                            12 <span class="font-weight-normal">Orang</span>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fa fa-female"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Putri</span>
                        <span class="info-box-number">
                            12 <span class="font-weight-normal">Orang</span>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-school"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Seluruh Lembaga</span>
                        <span class="info-box-number">
                            12 <span class="font-weight-normal">Lembaga</span>
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <?php if ($this->session->userdata('role') == 'MMU') { ?>
                <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="callout callout-success">
                        <h6>
                            Nomor urut tampil MMU Anda adalah <b class="text-success"><?= $undian ?></b>
                        </h6>
                        <p>
                            Pada pelaksanaan, urut tampil disesuaikan dengan jumlah peserta.
                            <br>Anda bisa cek urut tampil <i>real</i> <a class="text-success" href="<?= base_url() ?>perfome">di sini</a>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="callout callout-warning">
                    <h6>Langkah Aman</h6>
                    <p>
                        Agar data lebih aman, ubah username dan password secara berkala
                        <a class="text-success" href="<?= base_url() ?>profile">di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('home/js-home'); ?>