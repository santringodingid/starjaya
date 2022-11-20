<?php $this->load->view('partials/header'); ?>
<input type="hidden" id="url" value="<?= base_url() ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-2"></div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 mb-2">
                <input type="text" id="changeName" class="form-control form-control-sm w-100" placeholder="Tekan F2 lalu masukkan nama" autofocus onkeyup="loadData()">
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-2">
                <select id="changeCategory" onchange="loadData()" class="form-control form-control-sm w-100" style="width: 150px">
                    <option value="">..:Semua Kategori:..</option>
                    <option value="markets">TOKO</option>
                    <option value="customers">PELANGGAN</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2 mb-2">
                <button type="button" class="btn mr-2 btn-sm btn-primary btn-block" id="add-button">
                    <i class="fa fa-plus-circle"></i>
                    Tambah Data
                </button>
            </div>
        </div>
        <div class="row skeleton_loading__">
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
            <div class="col-lg-4">
                <div class="card skeleton py-2 mb-3"></div>
            </div>
        </div>
        <div class="row" id="show-master"></div>
    </section>
    <!-- /.content -->
    <div class="wrap-loading__" style="display: none">
        <div class="loading__ fade-in-loading__">
            <div class="wrapper-loading__">
                <div class="lds-dual-ring"></div>
                <span class="font-italic text-loading__">Ke pasar beli pepaya, tunggu sebentar, ya.....</span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-master" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2 justify-content-end">
                <h6 class="modal-title">Tambah Data <span id="type-name"></span></h6>
            </div>
            <form id="form-master" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="0">
                    <input type="hidden" name="table_type" id="table-type" value="0">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Nama</label>
                        <div class="col-sm-7 form-feedback">
                            <input type="text" name="name" id="name" class="form-control text-uppercase">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Alamat</label>
                        <div class="col-sm-7 form-feedback">
                            <textarea name="address" id="address" class="form-control text-capitalize"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-5 col-form-label">No. HP</label>
                        <div class="col-sm-7 form-feedback">
                            <input type="text" name="phone" id="phone" class="form-control" data-inputmask="'mask' : '999-999-999-999'" data-mask="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between p-2">
                    <button type="button" class="btn btn-danger btn-sm px-5" data-dismiss="modal">Batal</button>
                    <button type="submit" id="submit-button" class="btn btn-primary btn-sm px-5">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url() ?>template/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>template/plugins/jquery-validation/additional-methods.min.js"></script>
<?php $this->load->view('master/js-master'); ?>