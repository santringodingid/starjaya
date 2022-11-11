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
                            <div class="btn-group">
                                <i style="cursor: pointer" class="fas fa-ellipsis-v" data-toggle="dropdown" aria-expanded="true"></i>
                                <ul class="dropdown-menu dropdown-menu-right py-1 pr-0" x-placement="top-start" style="min-width: 0">
                                    <li style="cursor: pointer" class="<?= ($status == 'ORDERED') ? '' : 'd-none' ?>" onclick="approveOrder(<?= $data->id ?>)">
                                        <span class="dropdown-item text-xs">Konfirmasi</span>
                                    </li>
                                    <li style="cursor: pointer" class="<?= ($status == 'APPROVED') ? '' : 'd-none' ?>" onclick="deliverOrder(<?= $data->id ?>)">
                                        <span class="dropdown-item text-xs">Proses Kirim</span>
                                    </li>
                                    <li style="cursor: pointer" class="<?= ($status == 'DELIVERED') ? '' : 'd-none' ?>">
                                        <a href="<?= base_url() ?>ordering/done/<?= $data->id ?>" class="dropdown-item text-xs">Selesaikan</a>
                                    </li>
                                    <li style="cursor: pointer" class="<?= ($status == 'DONE') ? '' : 'd-none' ?>">
                                        <a href="<?= base_url() ?>ordering/done/<?= $data->id ?>" class="dropdown-item text-xs">Cetak Faktur</a>
                                    </li>
                                    <li style="cursor: pointer" onclick="detailOrder(<?= $data->id ?>)">
                                        <span class="dropdown-item text-xs">Lihat detail</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2 pb-3">
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