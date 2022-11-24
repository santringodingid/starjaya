<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderingModel extends CI_Model
{
    public function customer()
    {
        return $this->db->get('customers')->result_object();
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

        return $data->result_object();
    }

    public function getCustomerName($id)
    {
        $this->db->select('b.name, a.customer_id, a.status')->from('orders AS a');
        $this->db->join('customers AS b', 'a.customer_id = b.id');
        $data = $this->db->where('a.id', $id)->get()->row_object();

        if (!$data) {
            return [
                0,
                0
            ];
        }

        return [
            $data->name,
            $data->status
        ];
    }

    public function detailOrder($invoice)
    {
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
                    'amount' => number_format($d->amount, 0, ',', '.'),
                    'status' => $d->status
                ];
            }

            $amount = $this->db->select_sum('amount')->from('order_detail')->where([
                'order_id' => $invoice, 'status !=' => 'CANCELED'
            ])->get()->row_object();

            $item = $this->db->get_where('order_detail', [
                'order_id' => $invoice, 'status !=' => 'CANCELED'
            ])->num_rows();

            return [
                'status' => 200,
                'data' => $rows,
                'amount' => number_format($amount->amount, 0, ',', '.'),
                'item' => $item . '/' . $row
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

    public function approve()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('orders', ['id' => $id])->num_rows();
        if ($check <= 0) {
            return [
                'status' => 400,
                'message' => 'Data transaksi tidak valid'
            ];
        }

        $this->db->where('id', $id)->update('orders', [
            'status' => 'APPROVED',
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by' => $this->session->userdata('user_id')
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

    public function deliver()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('orders', ['id' => $id])->num_rows();
        if ($check <= 0) {
            return [
                'status' => 400,
                'message' => 'Data transaksi tidak valid'
            ];
        }

        $this->db->where('id', $id)->update('orders', ['status' => 'DELIVERED']);
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

    public function cancel()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('order_detail', ['id' => $id])->row_object();
        if (!$check) {
            return [
                'status' => 400,
                'message' => 'Data order tidak valid'
            ];
        }
        $order = $check->order_id;
        $product = $check->product_id;

        $this->db->where('id', $id)->update('order_detail', [
            'status' => 'CANCELED'
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        $this->db->where(['order_id' => $order, 'product_id' => $product])->delete('stock_temp');
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        //GET AMOUNT CANCELED
        $qCanceled = $this->db->select_sum('amount')->from('order_detail')->where([
            'order_id' => $order, 'status' => 'CANCELED'
        ])->get()->row_object();

        $result = $qCanceled->amount;
        if ($result == '') {
            $canceledAmount = 0;
        } else {
            $canceledAmount = $result;
        }

        $this->db->where('id', $order)->update('orders', ['canceled_amount' => $canceledAmount]);
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

    public function getQty()
    {
        $id = $this->input->post('id', true);
        $checkOrderDetail = $this->db->get_where('order_detail', ['id' => $id])->row_object();
        if (!$checkOrderDetail) {
            return [
                'status' => 400,
                'message' => 'Data order tidak valid'
            ];
        }

        $order = $checkOrderDetail->order_id;
        $product = $checkOrderDetail->product_id;
        $qty = $checkOrderDetail->qty;

        $checkProduct = $this->db->get_where('products', ['id' => $product])->row_object();
        if (!$checkProduct) {
            return [
                'status' => 400,
                'message' => 'Data produk tidak valid'
            ];
        }

        $unitAmount = $checkProduct->unit_amount;
        $unitStock = $checkProduct->stock;
        $countAllQtyOrder = $this->db->select_sum('qty')->from('stock_temp')->where([
            'product_id' => $product, 'order_id !=' => $order
        ])->get()->row_object();
        $allStock = $unitStock - $countAllQtyOrder->qty;

        if ($qty < $unitAmount) {
            $result = [
                'package' => 0,
                'unit' => $qty
            ];
        } else {
            $countPackage = floor($qty / $unitAmount);
            $packageToUnit = $countPackage * $unitAmount;
            $residual = $qty - $packageToUnit;
            if ($residual <= 0) {
                $result = [
                    'package' => $countPackage,
                    'unit' => 0
                ];
            } else {
                $result = [
                    'package' => $countPackage,
                    'unit' => $residual
                ];
            }
        }

        return [
            'status' => 200,
            'message' => 'Success',
            'data' => $result,
            'stock' => $allStock
        ];
    }

    public function update()
    {
        $id = $this->input->post('id', true);
        $package = $this->input->post('package', true);
        $unit = $this->input->post('unit', true);

        $checkOrderDetail = $this->db->get_where('order_detail', ['id' => $id])->row_object();
        if (!$checkOrderDetail) {
            return [
                'status' => 400,
                'message' => 'Data order tidak valid'
            ];
        }
        $orderID = $checkOrderDetail->order_id;
        $nominal = $checkOrderDetail->nominal;
        $product = $checkOrderDetail->product_id;
        $qtyBefore = $checkOrderDetail->qty;

        $checkProduct = $this->db->get_where('products', ['id' => $product])->row_object();
        if (!$checkProduct) {
            return [
                'status' => 400,
                'message' => 'Data produk tidak valid'
            ];
        }
        $unitAmount = $checkProduct->unit_amount;
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

        if ($qtyBefore == $qty) {
            return [
                'status' => 400,
                'message' => 'Kuantiti sama dengan sebelumnya'
            ];
        }

        $unitStock = $checkProduct->stock;
        $countAllQtyOrder = $this->db->select_sum('qty')->from('order_detail')->where([
            'product_id' => $product, 'id !=' => $id
        ])->get()->row_object();
        $allStock = $unitStock - $countAllQtyOrder->qty;
        if ($qty > $allStock) {
            return [
                'status' => 400,
                'message' => 'Stok melebihi batas tersedia'
            ];
        }



        $data = [
            'qty' => $qty,
            'discount' => $this->getDiscount($price * $qty),
            'amount' => $price * $qty,
            'status' => 'CHANGED'
        ];
        $this->db->where('id', $id)->update('order_detail', $data);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        $getOrder = $this->db->select_sum('amount')->from('order_detail')->where('order_id', $orderID)->get()->row_object();
        $this->db->where('id', $orderID)->update('orders', ['amount' => $getOrder->amount]);
        $this->db->where(['order_id' => $orderID, 'product_id' => $product])->update('stock_temp', ['qty' => $qty]);

        return [
            'status' => 200,
            'message' => 'Success'
        ];
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

    public function saveOrder()
    {
        $invoice = $this->input->post('id', true);
        $checkOrder = $this->db->get_where('orders', ['id' => $invoice])->row_object();
        $checkOrderDetail = $this->db->get_where('order_detail', [
            'order_id' => $invoice,
            'status !=' => 'CANCELED'
        ])->result_object();

        if (!$checkOrder) {
            return [
                'status' => 400,
                'message' => 'Nomor faktur tidak valid'
            ];
        }

        if (!$checkOrderDetail) {
            return [
                'status' => 400,
                'message' => 'Belum ada item yang diorder'
            ];
        }

        //GET AMOUNT CANCELED
        $qCanceled = $this->db->select_sum('amount')->from('order_detail')->where([
            'order_id' => $invoice, 'status' => 'CANCELED'
        ])->get()->row_object();

        $result = $qCanceled->amount;
        if ($result == '') {
            $canceledAmount = 0;
        } else {
            $canceledAmount = $result;
        }

        $amount = $checkOrder->amount - $canceledAmount;
        $discount = $this->getDiscount($amount);

        //UPDATE STOCK
        foreach ($checkOrderDetail as $dd) {
            $productOrder = $dd->product_id;
            $productQty = $dd->qty;
            $this->db->select('unit_amount, stock')->from('products');
            $getProductOrder = $this->db->where('id', $productOrder)->get()->row_object();
            if ($getProductOrder) {
                $this->db->where('id', $productOrder)->update('products', [
                    'stock' => $getProductOrder->stock - $productQty
                ]);
            }
        }

        //UPDATE TABLE ORDERS
        $this->db->where('id', $invoice)->update('orders', [
            'amount' => $checkOrder->amount,
            'canceled_amount' => $canceledAmount,
            'discount' => $discount,
            'updated_at' => date('Y-m-d H:i:s'),
            'status' => 'DONE'
        ]);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        //UPDATE TABLE ORDER_DETAIL
        $this->db->where(['order_id' => $invoice, 'status' => 'PROCCESS'])->update('order_detail', [
            'status' => 'APPROVED'
        ]);
        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Internal server error'
            ];
        }

        //DELETE DATA ORDER IN STOCK_TEMP TABLE
        $this->db->where('order_id', $invoice)->delete('stock_temp');
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

    public function dataprint($id)
    {
        //GET TABLE ORDERS AND CUSTOMERS
        $this->db->select('a.*, b.name')->from('orders AS a')->join('customers AS b', 'a.customer_id = b.id');
        $getOrder = $this->db->where('a.id', $id)->get()->row_object();
        if (!$getOrder) {
            return [
                'status' => 400,
                'message' => 'Data customer dan pesanan tidak valid'
            ];
        }

        $this->db->select('a.*, b.name AS product, b.unit_amount, c.name AS unit')->from('order_detail AS a');
        $this->db->join('products AS b', 'a.product_id = b.id');
        $this->db->join('units AS c', 'b.unit_id = c.id');
        $this->db->where(['a.order_id' => $id, 'status !=' => 'CANCELED']);
        $result = $this->db->order_by('a.id', 'ASC')->get()->result_object();
        if (!$result) {
            return [
                'status' => 400,
                'message' => 'Data barang tidak valid'
            ];
        }

        foreach ($result as $d) {
            $data[] = [
                'product' => $d->product,
                'qty' => $this->getDetailProductOrder($d->product_id, $d->qty),
                'unit' => $d->qty,
                'nominal' => $d->nominal,
                'amount' => $d->amount
            ];
        }

        $total = $getOrder->amount - $getOrder->canceled_amount;
        return [
            'status' => 200,
            'message' => 'Sukses',
            'id' => $id,
            'customer' => $getOrder->name,
            'sales' => $this->session->userdata('name'),
            'date' => dateTimeShortenFormat($getOrder->updated_at),
            'amount' => $total,
            'discount' => $getOrder->discount,
            'cash' => ($total - $getOrder->discount),
            'data' => $data
        ];
    }
}
