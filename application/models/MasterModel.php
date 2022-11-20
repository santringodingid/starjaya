<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterModel extends CI_Model
{
    public function save()
    {
        $id = $this->input->post('id', true);
        $tableType = $this->input->post('table_type', true);
        $name = $this->input->post('name', true);
        $address = $this->input->post('address', true);
        $phone = str_replace('-', '', $this->input->post('phone', true));

        // if ($tableType != 'markets' || $tableType != 'customers') {
        //     return [
        //         'status' => 400,
        //         'message' => 'Pastikan kategori sudah dipilih'
        //     ];
        // }

        if ($name == '' || $address == '' || $phone == '') {
            return [
                'status' => 400,
                'message' => 'Pastikan semua bidang inputan sudah diisi'
            ];
        }

        if ($tableType == 'markets') {
            $generator = date('Y') . mt_rand(1000, 9999);
        } else {
            $generator = mt_rand(1000, 9999) . date('Y');
        }

        if ($id == 0) {
            $data = [
                'id' => $generator,
                'name' => strtoupper($name),
                'address' => ucwords($address),
                'phone' => $phone,
                'created_at' => date("Y-m-d H:i:s")
            ];
            $this->db->insert($tableType, $data);
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 200,
                    'message' => 'Satu data berhasil ditambahkan'
                ];
            } else {
                return [
                    'status' => 400,
                    'message' => 'Kesalahan server'
                ];
            }
        } else {
            $data = [
                'name' => strtoupper($name),
                'address' => ucwords($address),
                'phone' => $phone,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->where('id', $id)->update($tableType, $data);
            if ($this->db->affected_rows() > 0) {
                return [
                    'status' => 200,
                    'message' => 'Satu data berhasil diubah'
                ];
            } else {
                return [
                    'status' => 200,
                    'message' => 'Tidak ada data yang berubah'
                ];
            }
        }
    }

    public function loadData()
    {
        $name = $this->input->post('name', true);
        $category = $this->input->post('category', true);

        $this->db->select('*')->from('customers');
        if ($category == 'customers' && $name != '') {
            $this->db->like('name', $name);
        }
        $resultCustomer = $this->db->order_by('created_at', 'DESC')->get();
        if ($category == '' || $category == 'customers') {
            $dataCustomer = $resultCustomer->result_object();
            $amountCustomer = $resultCustomer->num_rows();
        } else {
            $dataCustomer = '';
            $amountCustomer = 0;
        }

        $this->db->select('*')->from('markets');
        if ($category == 'markets' && $name != '') {
            $this->db->like('name', $name);
        }
        $resultMarket = $this->db->order_by('created_at', 'DESC')->get();
        if ($category == '' || $category == 'markets') {
            $dataMarket = $resultMarket->result_object();
            $amountMarket = $resultMarket->num_rows();
        } else {
            $dataMarket = '';
            $amountMarket = 0;
        }

        return [
            $dataCustomer,
            $amountCustomer,
            $dataMarket,
            $amountMarket
        ];
    }

    public function edit()
    {
        $id = $this->input->post('id', true);
        $tableType = $this->input->post('tableType', true);
        $check = $this->db->get_where($tableType, [
            'id' => $id
        ])->row_object();

        if (!$check) {
            return [
                'status' => 400,
                'message' => 'Data tidak ditemukan'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Success',
            'data' => [
                'name' => $check->name,
                'address' => $check->address,
                'phone' => $check->phone
            ]
        ];
    }
}
