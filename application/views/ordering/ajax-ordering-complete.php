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
                ];
                foreach ($data as $d) {
            ?>
                    <div class="row">
                        <div class="col-11 text-xs">
                            <span><?= $d['product'] ?></span> <br>
                            <div class="row pl-3">
                                <?= $d['qty'] ?> <i class="ml-3"><?= $statusText[$d['status']] ?></i>
                            </div>
                        </div>
                        <div class="col-1 text-right">
                            <div class="btn-group <?= ($d['status'] == 'CANCELED' || $statusOrder == 'DONE') ? 'd-none' : '' ?>">
                                <i style="cursor: pointer" class="fas fa-ellipsis-v" data-toggle="dropdown" aria-expanded="true"></i>
                                <ul class="dropdown-menu dropdown-menu-right py-1 pr-0" x-placement="top-start" style="min-width: 0">
                                    <li style="cursor: pointer">
                                        <span class="dropdown-item text-xs" onclick="editOrder(<?= $d['id'] ?>)">Edit</span>
                                    </li>
                                    <li style="cursor: pointer">
                                        <span class="dropdown-item text-xs text-danger" onclick="cancelOrder(<?= $d['id'] ?>)">Batal</span>
                                    </li>
                                </ul>
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
            <form action="<?= base_url() ?>ordering/setprint" method="post" target="_blank" class="<?= ($statusOrder != 'DONE') ? 'd-none' : '' ?>">
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