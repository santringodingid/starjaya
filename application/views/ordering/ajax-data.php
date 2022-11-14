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
                                <?= $data->id ?> <br>
                                <?= dateTimeShortenFormat($data->created_at) ?>
                            </small>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="row">
                        <div class="col-8 text-xs" style="cursor: pointer" title="Klik untuk lihat detail" onclick="detailOrder(<?= $data->id ?>)">
                            Rp. <?= number_format($data->amount, 0, ',', '.') ?> <br>
                            <i><?= $statusText[$status]; ?></i>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-sm btn-primary btn-block mt-1 <?= ($status == 'ORDERED') ? '' : 'd-none' ?>" onclick="approveOrder(<?= $data->id ?>)">
                                <i class="fas fa-clipboard-check fa-lg"></i> Konfirmasi
                            </button>
                            <button class="btn btn-sm btn-primary btn-block mt-1 <?= ($status == 'APPROVED') ? '' : 'd-none' ?>" onclick="deliverOrder(<?= $data->id ?>)">
                                <i class="fas fa-truck fa-lg"></i> Kirim
                            </button>
                            <a class="btn btn-sm btn-block btn-primary mt-1 <?= ($status == 'DELIVERED') ? '' : 'd-none' ?>" href="<?= base_url() ?>ordering/done/<?= $data->id ?>">
                                <i class="fas fa-file-invoice-dollar fa-lg"></i> Selesai
                            </a>
                            <form action="<?= base_url() ?>ordering/print" method="post" target="_blank" class="<?= ($status == 'DONE') ? '' : 'd-none' ?>" id="form-print">
                                <input type="hidden" name="id" value="<?= $data->id ?>">
                                <button type="submit" class="btn btn-primary btn-sm mt-1 btn-block">
                                    <i class="fas fa-print"></i> Print
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