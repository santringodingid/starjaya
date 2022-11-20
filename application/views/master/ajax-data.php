<?php
if ($customer) {
?>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <h6 class="text-center mb-3">Data Pelanggan (<?= $amountCustomer ?>)</h6>
        <?php
        foreach ($customer as $dc) {
        ?>
            <div class="card">
                <div class="card-body pt-2 pb-3">
                    <div class="row">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12">
                                    <span><?= $dc->name ?></span>
                                </div>
                                <div class="col-12">
                                    <small><?= $dc->address ?></small> <br>
                                    <small><?= $dc->phone ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-right">
                            <button class="btn btn-default btn-sm" onclick="editMaster(<?= $dc->id ?>, 'customers')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>

<?php
if ($market) {
?>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <h6 class="text-center mb-3">Data Toko (<?= $amountMarket ?>)</h6>
        <?php
        foreach ($market as $dm) {
        ?>
            <div class="card">
                <div class="card-body pt-2 pb-3">
                    <div class="row">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12">
                                    <span><?= $dm->name ?></span>
                                </div>
                                <div class="col-12">
                                    <small><?= $dm->address ?></small> <br>
                                    <small><?= $dm->phone ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 text-right">
                            <button class="btn btn-default btn-sm" onclick="editMaster(<?= $dm->id ?>, 'markets')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>