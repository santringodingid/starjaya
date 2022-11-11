<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductModel extends CI_Model
{
    public function categories()
    {
        return $this->db->get('categories')->result_object();
    }

    public function packages()
    {
        return $this->db->get('packages')->result_object();
    }

    public function units()
    {
        return $this->db->get('units')->result_object();
    }

    public function save()
    {
        $id = $this->input->post('id', true);
        $name = $this->input->post('name', true);
        $category = $this->input->post('category', true);
        $package = $this->input->post('package', true);
        $unit = $this->input->post('unit', true);
        $amount = $this->input->post('amount', true);

        if ($name == '' || $category == '' || $package == '' || $unit == '' || $amount == '') {
            return [
                'status' => 400,
                'message' => 'Pastikan semua bidang inputan sudah diisi'
            ];
        }

        if ($id == 0) {
            $data = [
                'id' => date('Y') . mt_rand(1000, 9999),
                'name' => strtoupper($name),
                'category_id' => $category,
                'package_id' => $package,
                'unit_id' => $unit,
                'unit_amount' => $amount,
                'package_price' => 0,
                'unit_price' => 0,
                'stock' => 0,
                'created_at' => date("Y-m-d H:i:s")
            ];
            $this->db->insert('products', $data);
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
                'category_id' => $category,
                'package_id' => $package,
                'unit_id' => $unit,
                'unit_amount' => $amount,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->where('id', $id)->update('products', $data);
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

        $this->db->select('a.*, b.name AS category, c.name AS package, d.name AS unit')->from('products AS a');
        $this->db->join('categories AS b', 'a.category_id = b.id');
        $this->db->join('packages AS c', 'a.package_id = c.id');
        $this->db->join('units AS d', 'a.unit_id = d.id');

        if ($category != '') {
            $this->db->where('a.category_id', $category);
        }

        if ($name != '') {
            $this->db->like('a.name', $name);
        }
        $result = $this->db->order_by('a.created_at', 'DESC')->get();

        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }

    public function edit()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('products', [
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
                'category' => $check->category_id,
                'package' => $check->package_id,
                'unit' => $check->unit_id,
                'amount' => $check->unit_amount
            ]
        ];
    }

    public function getImage()
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('products', ['id' => $id])->row_object();
        return $check->image;
    }

    public function upload($image)
    {
        $id = $this->input->post('id', true);
        $check = $this->db->get_where('products', ['id' => $id])->num_rows();
        if ($id == '' || $id == 0 || $check <= 0) {
            return [
                'status' => 400,
                'message' => 'Produk tidak ditemukan'
            ];
        }

        $this->db->where('id', $id)->update('products', [
            'image' => $image, 'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() <= 0) {
            return [
                'status' => 400,
                'message' => 'Server tidak merespon'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Image berhasil diupload'
        ];
    }

    public function print()
    {
        $this->db->select('a.*, b.name AS category, c.name AS package, d.name AS unit')->from('products AS a');
        $this->db->join('categories AS b', 'a.category_id = b.id');
        $this->db->join('packages AS c', 'a.package_id = c.id');
        $this->db->join('units AS d', 'a.unit_id = d.id');
        $result = $this->db->order_by('a.created_at', 'DESC')->get();

        return [
            $result->result_object(),
            $result->num_rows()
        ];
    }
}
