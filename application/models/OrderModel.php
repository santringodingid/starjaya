<?php

use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;

defined('BASEPATH') or exit('No direct script access allowed');

class OrderModel extends CI_Model
{
    public function setting()
    {
        $user = $this->session->userdata('user_id');
        $check = $this->db->get_where('orders', [
            'user_id' => $user, 'status' => 'ACTIVE'
        ])->row_object();

        if (!$check) {
            return [
                'invoice' => 0,
                'customer_id' => 0,
                'customer_name' => ''
            ];
        }

        $getCustomer = $this->db->get_where('customers', ['id' => $check->customer_id])->row_object();

        return [
            'invoice' => $check->id,
            'customer_id' => $check->customer_id,
            'customer_name' => $getCustomer->name
        ];
    }

    public function customer()
    {
        return $this->db->get('customers')->result_object();
    }

    public function setInvoice()
    {
        $user = $this->session->userdata('user_id');
        $status = $this->input->post('status', true);
        $customer = $this->input->post('customer', true);
        $invoice = $this->input->post('invoice', true);

        if ($status == 'ADD') {
            //CHECK ACTIVE INVOICE
            $check = $this->db->get_where('orders', [
                'user_id' => $user, 'status' => 'ACTIVE'
            ])->num_rows();

            if ($check > 0) {
                return [
                    'status' => 400,
                    'message' => 'Masih ada faktur yang belum selesai'
                ];
            }

            //CHECK MARKET
            $checkCustomer = $this->db->get_where('customers', ['id' => $customer])->num_rows();
            if ($checkCustomer <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Toko yang dipilih tidak valid'
                ];
            }

            $id = mt_rand(1000, 9999) . date('Y') . date('m') . date('d');
            $this->db->insert('orders', [
                'id' => $id,
                'customer_id' => $customer,
                'amount' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user,
                'status' => 'ACTIVE'
            ]);
            if ($this->db->affected_rows() <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Internal server error'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Sukses'
            ];
        } elseif ($status == 'DONE') {
            $check = $this->db->get_where('orders', ['id' => $invoice])->num_rows();
            if ($check <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Nomor faktur tidak valid'
                ];
            }

            $this->db->where('id', $invoice)->update('orders', [
                'status' => 'ORDERED'
            ]);

            if ($this->db->affected_rows() <= 0) {
                return [
                    'status' => 400,
                    'message' => 'Internal server error'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Sukses'
            ];
        } else {
            return [
                'status' => 400,
                'message' => 'Method tidak valid'
            ];
        }
    }

    public function getProduct()
    {
        $keyword = $this->input->post('keyword', true);

        $data = $this->db->select('*')->like('name', $keyword)->get('products')->result_object();
        if ($data) {
            foreach ($data as $d) {
                $response[] = [
                    'label' => $d->name,
                    'value' => $d->name,
                    'id' => $d->id
                ];
            }
        }

        return $response;
    }

    public function getDetailProduct()
    {
        $id = $this->input->post('id', true);
        $this->db->select('a.*, b.name AS package, c.name AS unit')->from('products AS a');
        $this->db->join('packages AS b', 'a.package_id = b.id');
        $this->db->join('units AS c', 'a.unit_id = c.id');
        $this->db->where('a.id', $id);
        $data = $this->db->get()->row_object();
        if (!$data) {
            return [
                'status' => 400,
                'message' => 'Data tidak ditemukan'
            ];
        }

        // AMBIL QTY PRODUK INI YANG MASIH DALAM PROSES ORDER
        $stock = $this->getProductStock($id);
        $packageStock = floor($stock / $data->unit_amount);
        $unitStock = $stock - ($data->unit_amount * $packageStock);
        if ($packageStock <= 0) {
            $stockFinal = $data->unit_amount . ' ' . $data->unit;
        } else {
            if ($unitStock <= 0) {
                $stockFinal = $packageStock . ' ' . $data->package;
            } else {
                $stockFinal = $packageStock . ' ' . $data->package . ' + ' . $unitStock . ' ' . $data->unit;
            }
        }

        return [
            'status' => 200,
            'message' => 'sukses',
            'data' => [
                'package' => $data->package . ' x ' . $data->unit_amount . ' ' . $data->unit,
                'price' => number_format($data->package_price + 2000, 0, ',', '.') . ' / ' . number_format(ceil(($data->package_price + 2000) / $data->unit_amount), 0, ',', '.'),
                'stock' => $stockFinal,
                'package_price' => number_format($data->package_price + 2000, 0, ',', '.')
            ]
        ];
    }

    public function getProductStock($id)
    {
        $stockProduct = $this->db->select('stock')->from('products')->where('id', $id)->get()->row_object();
        $this->db->select_sum('qty')->from('stock_temp');
        $stockTemp = $this->db->where('product_id', $id)->get()->row_object();

        if (!$stockTemp) {
            $stockTemp = 0;
        } else {
            $stockTemp = $stockTemp->qty;
        }

        // JIKA TIDAK ADA ATAU NOL BERARTI STOK PAKET DAN UNIT AMBIL DARI TABLE PRODUCTS
        return $stockProduct->stock - $stockTemp;
    }

    public function loadData()
    {
        $status = $this->input->post('status', true);
        $customer = $this->input->post('customer', true);
        $startDate = $this->input->post('startDate', true);
        $endDate = $this->input->post('endDate', true);

        $this->db->select('a.*, b.name AS customer, b.address');
        $this->db->from('orders AS a')->join('customers AS b', 'b.id = a.customer_id');
        $this->db->where('a.status !=', 'ACTIVE');
        if ($status != '') {
            $this->db->where('a.status', $status);
        }
        if ($customer != '') {
            $this->db->where('a.customer_id', $customer);
        }
        if ($startDate != '' && $endDate != '') {
            $start = date('Y-m-d H:i:s', strtotime($startDate . ' 00:00:00'));
            $end = date('Y-m-d H:i:s', strtotime($endDate . ' 23:59:59'));
            $this->db->where('a.created_at >=', $start);
            $this->db->where('a.created_at <=', $end);
        }
        $data = $this->db->order_by('a.created_at', 'DESC')->get();

        $this->db->select('SUM(amount) AS total')->from('orders');
        $this->db->where('status !=', 'ACTIVE');
        if ($status != '') {
            $this->db->where('status', $status);
        }
        if ($customer != '') {
            $this->db->where('customer_id', $customer);
        }
        $total = $this->db->get()->row_object();

        return [
            $data->result_object(),
            $data->num_rows(),
            $total->total
        ];
    }

    public function loadAdd()
    {
        $invoice = $this->input->post('invoice', true);
        $this->db->select('a.*, b.name')->from('order_detail AS a');
        $this->db->join('products AS b', 'a.product_id = b.id');
        $this->db->where('a.order_id', $invoice);
        $result = $this->db->order_by('a.id', 'DESC')->get();

        $data = $result->result_object();
        $row = $result->num_rows();
        if ($data) {
            foreach ($data as $d) {
                $rows[] = [
                    'id' => $d->id,
                    'product' => $d->name,
                    'qty' => $this->getDetailProductOrder($d->product_id, $d->qty),
                    'nominal' => number_format($d->nominal, 0, ',', '.'),
                    'discount' => number_format($d->discount, 0, ',', '.'),
                    'amount' => number_format($d->amount - $d->discount, 0, ',', '.'),
                    'status' => $d->status
                ];
            }

            $amount = $this->db->select('SUM(amount) AS amount, SUM(discount) AS discount')->from('order_detail')->where('order_id', $invoice)->get()->row_object();
            $total = $amount->amount - $amount->discount;
            return [
                'status' => 200,
                'data' => $rows,
                'amount' => number_format($total, 0, ',', '.'),
                'item' => $row
            ];
        } else {
            return [
                'status' => 400,
                'data' => 0,
                'amount' => 0,
                'item' => 0
            ];
        }
    }

    public function getDetailProductOrder($id, $qty)
    {
        $this->db->select('a.unit_amount, b.name AS package, c.name AS unit')->from('products AS a');
        $this->db->join('packages AS b', 'a.package_id = b.id');
        $this->db->join('units AS c', 'a.unit_id = c.id');
        $data = $this->db->where('a.id', $id)->get()->row_object();

        // return $this->db->last_query();

        $package = $data->package;
        $unit = $data->unit;
        $unitAmount = $data->unit_amount;

        if ($qty < $unitAmount) {
            $result = $qty . ' ' . $unit;
        } else {
            $countPackage = floor($qty / $unitAmount);
            $packageToUnit = $countPackage * $unitAmount;
            $residual = $qty - $packageToUnit;
            if ($residual <= 0) {
                $result = $countPackage . ' ' . $package;
            } else {
                $result = $countPackage . ' ' . $package . ' + ' . ($qty - $packageToUnit) . ' ' . $unit;
            }
        }

        return $result;
    }

    public function save()
    {
        $orderId = $this->input->post('order_id', true);
        $productId = $this->input->post('product_id', true);
        $package = $this->input->post('package', true);
        $unit = $this->input->post('unit', true);
        $nominal = (int)str_replace('.', '', $this->input->post('nominal', true));

        //GET PRODUCTS
        $product = $this->db->get_where('products', ['id' => $productId])->row_object();
        if (!$product) {
            return [
                'status' => 400,
                'message' => 'Produk tidak valid'
            ];
        }

        if ($unit <= 0 && $package <= 0) {
            return [
                'status' => 400,
                'message' => 'Semua QTY tidak boleh NOL/Kosong'
            ];
        }

        if ($nominal <= 0 || $nominal == '') {
            return [
                'status' => 400,
                'message' => 'Nominal belum diisi'
            ];
        }

        $unitAmount = $product->unit_amount;
        $nominal = ceil($nominal / $unitAmount);

        if ($package <= 0) {
            //YANG DIBELI SATUAN
            $qty = $unit;
            $price = $this->changePrice($nominal, $qty);
        } else {
            if ($unit <= 0) {
                //YANG DIBELI PAKETAN
                $qty = $unitAmount * $package;
                $price = $nominal;
            } else {
                //YANG DIBELI SATUAN + PAKETAN
                $qty = ($unitAmount * $package) + $unit;
                $price = $this->changePrice($nominal, $qty);
            }
        }

        $getQty = $this->getProductStock($productId);
        if ($qty > $getQty) {
            return [
                'status' => 400,
                'message' => 'Stok tidak cukup'
            ];
        }

        $this->db->insert('order_detail', [
            'order_id' => $orderId,
            'product_id' => $productId,
            'qty' => $qty,
            'nominal' => $price,
            'discount' => $this->getDiscount($price * $qty),
            'amount' => $price * $qty,
            'status' => 'PROCCESS'
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Server tidak merespon'
            ];
        }

        $this->db->insert('stock_temp', [
            'order_id' => $orderId,
            'product_id' => $productId,
            'qty' => $qty
        ]);

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function getDiscount($nominal)
    {
        $endAmount = (int)substr($nominal, -3);

        if ($endAmount > 0) {
            if ($endAmount < 500) {
                return (int)$endAmount;
            } else if ($endAmount > 500) {
                return $endAmount - 500;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function changePrice($nominal, $qty)
    {
        $total = $nominal * $qty;
        $endAmount = (int)substr($total, -3);

        if ($endAmount > 0) {
            if ($endAmount < 500) {
                $added = 500 - $endAmount;
                return $nominal + ceil($added / $qty);
            } else if ($endAmount > 500) {
                $added = 1000 - $endAmount;
                return $nominal + ceil($endAmount / $qty);
            } else {
                return $nominal;
            }
        } else {
            return $nominal;
        }
    }

    public function deleteDetail()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('order_detail', ['id' => $id])->row_object();
        if (!$check) {
            return [
                'status' => 400,
                'message' => 'Data transaksi tidak valid'
            ];
        }
        $order = $check->order_id;
        $product = $check->product_id;

        $this->db->where(['order_id' => $order, 'product_id' => $product])->delete('stock_temp');
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        $this->db->where('id', $id)->delete('order_detail');
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function deleteOrder()
    {
        $invoice = $this->input->post('id', true);
        $this->db->where('order_id', $invoice)->delete('order_detail');
        $this->db->where('id', $invoice)->delete('orders');
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        $this->db->where('order_id', $invoice)->delete('stock_temp');

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function saveOrder()
    {
        $invoice = $this->input->post('id', true);
        $checkOrder = $this->db->get_where('orders', ['id' => $invoice])->num_rows();
        $checkOrderDetail = $this->db->get_where('order_detail', ['order_id' => $invoice])->num_rows();

        if ($checkOrder <= 0) {
            return [
                'status' => 400,
                'message' => 'Nomor faktur tidak valid'
            ];
        }

        if ($checkOrderDetail <= 0) {
            return [
                'status' => 400,
                'message' => 'Belum ada item yang diorder'
            ];
        }

        //AMBIL JUMLAH QTY DI ORDER_DETAIL
        $getCount = $this->getTotalOrder($invoice);
        $amount = $getCount->amount;

        $this->db->where('id', $invoice)->update('orders', [
            'amount' => $amount,
            'status' => 'ORDERED'
        ]);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sukses'
        ];
    }

    public function getTotalOrder($id)
    {
        $this->db->select('SUM(qty) AS qty, SUM(amount) AS amount')->from('order_detail');
        return $this->db->where('order_id', $id)->get()->row_object();
    }
}
