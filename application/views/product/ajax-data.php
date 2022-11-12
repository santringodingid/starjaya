<?php
if ($product) {
?>
    <div class="col-12 text-center mb-3">
        <b>Total : <?= $amount ?> Produk</b>
    </div>
    <?php
    $no = 1;
    foreach ($product as $data) {
        $imagePath = FCPATH . 'assets/images/products/' . $data->image;

        if (file_exists($imagePath) === FALSE || $imagePath == NULL || $data->image == NULL) {
            $avatar = base_url('assets/images/products/404.png');
        } else {
            $avatar = base_url('assets/images/products/' . $data->image);
        }

        $stock = $data->stock;
        $unitAmount = $data->unit_amount;
        $packageStock = floor($stock / $unitAmount);
        $unitStock = $stock - ($unitAmount * $packageStock);
        if ($packageStock <= 0) {
            $stock = $unitAmount . ' ' . $data->unit;
        } else {
            if ($unitStock <= 0) {
                $stock = $packageStock . ' ' . $data->package;
            } else {
                $stock = $packageStock . ' ' . $data->package . ' + ' . $unitStock . ' ' . $data->unit;
            }
        }

        $roleUser = $this->session->userdata('role');
        if ($roleUser == 'SALER' || $roleUser == 'COURIER') {
            $packagePrice = $data->package_price + 2000;
            $unitPrice = ceil($packagePrice / $unitAmount);
        } else {
            $packagePrice = $data->package_price;
            $unitPrice = $data->unit_price;
        }
    ?>
        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row p-0">
                        <div class="col-4" style="cursor: pointer">
                            <img class="w-100" src="<?= $avatar ?>" alt="" onclick="previewImage(this)" alt="FOTO PRODUK <?= $data->name ?>">
                        </div>
                        <div class="col-8">
                            <div class="row py-2 pr-2 align-middle">
                                <div class="col-12">
                                    <b><?= $data->name ?></b>
                                </div>
                                <div class="col-12">
                                    <?= $data->package ?> x <?= $unitAmount . ' ' . $data->unit ?>
                                </div>
                                <div class="col-12">
                                    Rp. <?= number_format($packagePrice, 0, ',', '.') ?> x
                                    Rp. <?= number_format($unitPrice, 0, ',', '.') ?>
                                </div>
                                <div class="col-12">
                                    <span class="text-success">
                                        Stock : <?= $stock ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer row p-2">
                    <div class="col-6">
                        <span class="align-middle badge badge-success text-xs">
                            <?= $data->category ?>
                        </span>
                    </div>
                    <div class="col-6 text-right">
                        <div class="btn-group">
                            <button type="button" title="Edit data" onclick="editProduct(<?= $data->id ?>)" class="btn btn-default btn-xs">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button data-toggle="modal" data-target="#modal-upload" onclick="uploadImage(<?= $data->id ?>)" type="button" class="btn btn-default btn-xs">
                                <i class="fas fa-upload"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>