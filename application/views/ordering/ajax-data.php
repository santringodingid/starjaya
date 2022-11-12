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
                <div class="card-header text-xs">
                    <div class="row">
                        <div class="col-5">
                            <i class="fas fa-shopping-bag"></i>
                            <?= $data->id ?>
                        </div>
                        <div class="col-6">
                            <?= datetimeIDShortFormat($data->created_at) ?>
                        </div>
                        <div class="col-1 text-right">
                            <i class="fas fa-clipboard-check fa-lg <?= ($status == 'ORDERED') ? '' : 'd-none' ?>" style="cursor: pointer" onclick="approveOrder(<?= $data->id ?>)"></i>
                            <i style="cursor: pointer" onclick="deliverOrder(<?= $data->id ?>)" class="fas fa-truck fa-lg <?= ($status == 'APPROVED') ? '' : 'd-none' ?>"></i>
                            <a class="<?= ($status == 'DELIVERED') ? '' : 'd-none' ?>" href="<?= base_url() ?>ordering/done/<?= $data->id ?>"><i class="fas fa-file-invoice-dollar fa-lg"></i></a>
                            <form action="<?= base_url() ?>ordering/setprint" method="post" target="_blank" class="<?= ($status == 'DONE') ? '' : 'd-none' ?>" id="form-print">
                                <input type="hidden" name="id" value="<?= $data->id ?>">
                                <i style="cursor: pointer" class="fas fa-print fa-lg" onclick="printOut()"></i>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2 pb-3" title="Klik untuk lihat detail" style="cursor: pointer" onclick="detailOrder(<?= $data->id ?>)">
                    <div class="row">
                        <div class="col-7">
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-primary"><?= $data->customer ?></small>
                                </div>
                                <div class="col-12">
                                    <small><?= $data->address ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-5 text-xs">
                            Rp. <?= number_format($data->amount, 0, ',', '.') ?> <br>
                            <i><?= $statusText[$status]; ?></i>
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