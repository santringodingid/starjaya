<div class="card">
    <?php
    if ($status == 200) {
    ?>
        <div class="card-header py-2">
            <div class="row">
                <div class="col-9">
                    <small>Pesanan dari :</small><br>
                    <span class="text-primary"><?= $customer ?></span>
                </div>
                <div class="col-3 text-right pt-2">
                    <a href="<?= base_url() ?>ordering">Kembali</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-2 pb-0">
            <?php
            if ($data) {
                $statusText = [
                    'PROCCESS' => '<small class="text-warning">Menunggu konfirmasi</small>',
                    'CHANGED' => '<small class="text-warning">Ada perubahan</small>',
                    'CANCELED' => '<small class="text-danger">Dibatalkan</small>',
                    'APPROVED' => '<small class="text-success">Disetujui</small>',
                    'RETURED' => '<small class="text-danger">Diretur</small>',
                ];
                foreach ($data as $d) {
            ?>
                    <div class="row">
                        <div class="col-11">
                            <span class="text-xs"><?= $d['product'] ?></span> <br>
                            <div class="row pl-3">
                                <span onclick="editOrder(<?= $d['id'] ?>)" class="text-primary <?= ($d['status'] == 'CANCELED' || $statusOrder == 'DONE') ? 'd-none' : '' ?>"" style=" cursor: pointer" title="Klik untuk ubah kuantiti">
                                    <?= $d['qty'] ?>
                                </span>
                                <span class="<?= ($d['status'] == 'CANCELED' || $statusOrder == 'DONE') ? '' : 'd-none' ?>">
                                    <?= $d['qty'] ?>
                                </span>
                                <i class="ml-3"><?= $statusText[$d['status']] ?></i>
                            </div>
                        </div>
                        <div class="col-1 text-right">
                            <div class="<?= ($d['status'] == 'CANCELED' || $statusOrder == 'DONE') ? 'd-none' : '' ?>">
                                <i style="cursor: pointer" class="fas fa-trash text-danger" onclick="cancelOrder(<?= $d['id'] ?>)"></i>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-3">
            <?php
                }
            }
            ?>
        </div>
        <div class="card-footer">
            <button onclick="saveOrder()" class="btn btn-primary btn-sm btn-block <?= ($statusOrder != 'DELIVERED') ? 'd-none' : '' ?>">
                Selesaikan Pesanan
            </button>
            <form action="<?= base_url() ?>ordering/print" method="post" target="_blank" class="<?= ($statusOrder != 'DONE') ? 'd-none' : '' ?>">
                <input type="hidden" name="id" value="<?= $order ?>">
                <button type="submit" class="btn btn-primary btn-sm btn-block">
                    Print Out Faktur
                </button>
            </form>
        </div>
    <?php
    } else {
        echo '<div class="py-4 text-center">';
        echo '<h6 class="text-danger text-center">TIDAK ADA DATA UNTUK DITAMPILKAN</h6>';
        echo '<a href="' . base_url() . 'ordering">Kembali</a>';
        echo '</div>';
    }
    ?>
</div>