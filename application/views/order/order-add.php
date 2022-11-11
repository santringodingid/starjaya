<?php $this->load->view('partials/header'); ?>
<input type="hidden" id="url" value="<?= base_url() ?>">
<input type="hidden" id="invoice" value="<?= $setting['invoice'] ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-md-7 col-lg-7 col-xl-7 mb-2">
                <?php if ($setting['invoice'] > 0) { ?>
                    <h6 class="mb-0"><span class="text-primary"> <?= $setting['customer_name'] ?></span></h6>
                    <small>Invoice Number : <b><?= $setting['invoice'] ?></b></small>
                <?php } else { ?>
                    <a href="<?= base_url() ?>order" class="btn btn-primary btn-sm px-5">
                        <i class="fas fa-list"></i> Lihat data
                    </a>
                <?php } ?>
            </div>
            <div class="col-6 col-md-3 col-lg-3 col-xl-3 mb-2">
                <?php if ($setting['invoice'] <= 0) { ?>
                    <select id="changeCustomer" class="form-control form-control-sm w-100">
                        <option value="">..:Pilih Toko:..</option>
                        <?php
                        if ($customer) {
                            foreach ($customer as $c) {
                        ?>
                                <option value="<?= $c->id ?>"><?= $c->name ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                <?php
                } else {
                ?>
                    <button type="button" onclick="cancelOrder()" id="remove-invoice" class="btn btn-sm btn-danger btn-block">
                        <i class="fa fa-trash"></i> Batalkan Pemesanan
                    </button>
                <?php
                }
                ?>
            </div>
            <div class="col-6 col-md-2 col-lg-2 col-xl-2 mb-3">
                <?php
                if ($setting['invoice'] <= 0) {
                ?>
                    <button type="button" id="add-invoice" class="btn btn-sm btn-primary btn-block">
                        <i class="fa fa-plus-circle"></i> Buat Nomor Faktur
                    </button>
                <?php
                } else {
                ?>
                    <button type="button" onclick="saveOrder()" class="btn btn-sm btn-success btn-block">
                        <i class="far fa-check-circle"></i> Selesaikan
                    </button>
                <?php
                }
                ?>
            </div>
        </div>
        <hr class="mt-0">
        <?php if ($setting['invoice'] != 0) { ?>
            <div class="row">
                <div class="col-md-12 col-lg-5 col-xl-5 mb-3">
                    <form id="form-order" autocomplete="off">
                        <input type="text" autofocus name="name" id="product-name" class="form-control mb-3" placeholder="Ketik nama barang">
                        <input type="hidden" name="order_id" value="<?= $setting['invoice'] ?>">
                        <input type="hidden" name="product_id" id="product-id" value="0">
                        <div class="row skeleton_loading_product__" style="display: none">
                            <div class="col-12">
                                <div class="card skeleton py-5 mb-3"></div>
                            </div>
                        </div>
                        <div class="callout callout-success text-xs py-2" id="product-info" style="display: none;">
                            <div class="row">
                                <div class="col-6">
                                    <span id="package-info"></span>
                                </div>
                                <div class="col-6">
                                    <span id="price-info"></span>
                                </div>
                            </div>
                            <hr class="my-1">
                            <div class="text-success text-center">
                                Stok tersedia : <span id="stock-info"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-3">
                                <input value="0" type="number" name="package" id="package" class="form-control" placeholder="QTY PAKET">
                            </div>
                            <div class="col-3">
                                <input value="0" type="number" name="unit" id="unit" class="form-control" placeholder="QTY SATUAN">
                            </div>
                            <div class="col-6">
                                <input type="text" name="nominal" id="nominal" class="form-control" placeholder="Nominal">
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary btn-sm btn-block mt-3" id="save-order">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
                <div class="col-md-12 col-lg-7 col-xl-7">
                    <div class="row skeleton_loading__" id="skeleton_loadadd">
                        <div class="col-12">
                            <div class="card skeleton py-4 mb-3"></div>
                        </div>
                        <div class="col-12">
                            <div class="card skeleton py-4 mb-3"></div>
                        </div>
                    </div>
                    <div id="show-data"></div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-12">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-circle fa-5x text-danger"></i>
                        <h6 class="pt-4">Opppss..! Pilih toko dan buat nomor faktur untuk memulai transaksi</h6>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
    <!-- /.content -->
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
<script src="<?= base_url() ?>assets/js/order-add.js"></script>