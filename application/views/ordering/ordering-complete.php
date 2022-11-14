<?php $this->load->view('partials/header'); ?>
<input type="hidden" id="url" value="<?= base_url() ?>">
<input type="hidden" id="id" value="<?= $id ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row skeleton_loading__">
            <div class="col-12">
                <div class="card skeleton py-5 mb-3"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6" id="show-data"></div>
        </div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2 justify-content-end">
                <h6 class="modal-title">Form Edit Pesanan</h6>
            </div>
            <form id="form-edit" autocomplete="off" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id-edit" value="0">
                    <div class="callout callout-success text-sm py-2">
                        <div class="text-success text-center">
                            Stok tersedia : <span id="stock-info"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input value="0" type="number" name="package" id="package" class="form-control" placeholder="QTY PAKET">
                        </div>
                        <div class="col-6">
                            <input value="0" type="number" name="unit" id="unit" class="form-control" placeholder="QTY SATUAN">
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer justify-content-between p-2">
                <button id="remove-image" type="button" class="btn btn-danger btn-sm px-5" data-dismiss="modal">Batal</button>
                <button type="button" id="submit-button-edit" class="btn btn-primary btn-sm px-5">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- LOADING -->
<div class="wrap-loading__" style="display: none">
    <div class="loading__ fade-in-loading__">
        <div class="wrapper-loading__">
            <div class="lds-dual-ring"></div>
            <span class="font-italic text-loading__">Ke pasar beli pepaya, tunggu sebentar, ya.....</span>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/autoNumeric.js"></script>
<?php $this->load->view('ordering/js-ordering-complete'); ?>