<div class="card mb-0">
    <div class="card-body p-0 pb-2" style="max-height: 60.2vh; overflow: auto;">
        <table class="table table-head-fixed table-hover table-sm">
            <tbody>
                <?php
                $statusText = [
                    'PROCCESS' => '<small class="text-warning">- Menunggu konfirmasi</small>',
                    'CHANGED' => '<small class="text-warning">- Ada perubahan</small>',
                    'CANCELED' => '<small class="text-danger">- Dibatalkan</small>',
                    'APPROVED' => '<small class="text-success">- Disetujui</small>',
                    'RETURED' => '<small class="text-danger">- Diretur</small>',
                ];
                if ($status == 200) {
                    foreach ($data as $d) {
                ?>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-7">
                                        <small><?= $d['product'] ?></small>
                                    </div>
                                    <div class="col-5">
                                        <?= $statusText[$d['status']] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <small class="text-left"><?= $d['qty'] ?></small>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-left"><?= $d['price'] ?></small>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-center"><?= $d['nominal'] ?></small>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-right"><?= $d['amount'] ?></small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td class="text-center" colspan="7">
                            <b class="text-danger">Tidak ada data</b>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer row justify-content-between py-2">
        <div class="col-lg-6 text-center text-lg-left">
            Item : <b><?= $item ?> barang</b>
        </div>
        <div class="col-lg-6 text-center text-lg-right">
            <b>Total : Rp. <?= $amount ?></b>
        </div>
    </div>
</div>