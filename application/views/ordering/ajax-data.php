<?php
$statusText = [
    'ORDERED' => '<small class="text-warning"><i class="fas fa-ban"></i> Menunggu konfirmasi</small>',
    'APPROVED' => '<small class="text-primary"><i class="fas fa-truck-loading"></i> Menunggu pengiriman</small>',
    'DELIVERED' => '<small class="text-primary"><i class="fas fa-shipping-fast"></i> Sedang dikirim</small>',
    'DONE' => '<small class="text-success"><i class="fas fa-receipt"></i> Transaksi selesai</small>',
];
if ($datas) {
    $no = 1;
    foreach ($datas as $data) {
        $status = $data->status;
?>
        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body pt-2 pb-3">
                    <div class="row">
                        <div class="col-8">
                            <small class="text-bold"><?= $data->customer ?></small> <br>
                            <small class="text-muted"><?= $data->address ?></small>
                        </div>
                        <div class="col-4">
                            <small>
                                <?php
                                $roleUser = $this->session->userdata('role');
                                $grandTotal = $data->amount - $data->canceled_amount;
                                if ($grandTotal <= 0 && $roleUser == 'DEV' || $grandTotal <= 0 && $roleUser == 'ADMIN') {
                                ?>
                                    <span style="cursor: pointer" onclick="deleteTransaction(<?= $data->id ?>)"><?= $data->id ?></span>
                                <?php
                                } else {
                                ?>
                                    <?= $data->id ?>
                                <?php
                                }
                                ?>
                                <br>
                                <small class="text-muted">
                                    <?= dateTimeShortenFormat($data->created_at) ?>
                                </small>
                            </small>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="row">
                        <div class="col-8 text-xs" style="cursor: pointer" title="Klik untuk lihat detail" onclick="detailOrder(<?= $data->id ?>)">
                            Rp. <?= number_format($data->amount - $data->canceled_amount, 0, ',', '.') ?> <br>
                            <i><?= $statusText[$status]; ?></i>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-xs btn-primary btn-block mt-1 <?= ($status == 'ORDERED') ? '' : 'd-none' ?>" onclick="approveOrder(<?= $data->id ?>)">
                                Konfirmasi
                            </button>
                            <button class="btn btn-xs btn-primary btn-block mt-1 <?= ($status == 'APPROVED') ? '' : 'd-none' ?>" onclick="deliverOrder(<?= $data->id ?>)">
                                Kirim
                            </button>
                            <a class="btn btn-xs btn-block btn-success mt-1 <?= ($status == 'DELIVERED') ? '' : 'd-none' ?>" href="<?= base_url() ?>ordering/done/<?= $data->id ?>">
                                Selesaikan
                            </a>
                            <form action="<?= base_url() ?>ordering/print" method="post" target="_blank" class="<?= ($status == 'DONE') ? '' : 'd-none' ?>" id="form-print">
                                <input type="hidden" name="id" value="<?= $data->id ?>">
                                <button type="submit" class="btn btn-default btn-xs mt-1 btn-block">
                                    Print
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
} else {
    ?>
    <div class="col-12 text-center py-5">
        <i class="fas fa-exclamation-circle fa-5x text-danger"></i>
        <h6 class="pt-4">Opppss..! Tidak ada data untuk dimuat...</h6>
    </div>
<?php
}
?>