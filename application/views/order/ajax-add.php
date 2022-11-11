<div class="card">
    <div class="card-body px-3 pt-2 pb-0">
        <?php
        if ($status == 200) {
            foreach ($data as $d) {
        ?>
                <div class="row">
                    <div class="col-11 text-xs">
                        <span><?= $d['product'] ?></span> <br>
                        <div class="row pl-2">
                            <div class="col-4">
                                <?= $d['qty'] ?>
                            </div>
                            <div class="col-3">
                                <?= $d['nominal'] ?>
                            </div>
                            <div class="col-1">
                                <?= $d['discount'] ?>
                            </div>
                            <div class="col-3">
                                <?= $d['amount'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 text-right text-danger">
                        <i style="cursor: pointer" class="fas fa-trash" onclick="deleteDetail(<?= $d['id'] ?>)"></i>
                    </div>
                </div>
                <hr class="mt-2 mb-2">
            <?php
            }
        } else {
            ?>
            <b class="text-danger">Tidak ada data</b>
        <?php
        }
        ?>
    </div>
    <div class="card-footer row py-2 justify-content-between">
        <div class="col-6">
            Item : <b><?= $item ?></b> barang
        </div>
        <div class="text-right col-6">
            <b>Total : Rp. <?= $amount ?></b>
        </div>
    </div>
</div>