<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-12">
                Hal. ini masih dalam pengembangan
            </div>
        </div>
        <hr>
        <div class="row">
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