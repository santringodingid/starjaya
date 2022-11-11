<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeModel extends CI_Model
{
    public function loadData()
    {
        //PARTICIAPNTS
        $total = $this->db->select('COUNT(id) AS total')->from('participants')->get()->row_object();
        //CATEGORY
        $this->db->select('COUNT(id) AS total, category')->from('participants');
        $category = $this->db->group_by('category')->get()->result_object();
        //SCHOOL
        $school = $this->db->select('COUNT(DISTINCT(school_id)) AS total')->from('participants')->get()->row_object();

        $male = 0;
        $female = 0;

        foreach ($category as $c) {
            if ($c->category == 1) {
                $male = $c->total;
            } else {
                $female = $c->total;
            }
        }

        return [
            $total->total,
            $male,
            $female,
            $school->total
        ];
    }

    public function getUndian()
    {
        $data = $this->db->get_where('schools', [
            'id' => $this->session->userdata('user_id')
        ])->row_object();
        if ($data) {
            return $data->undian;
        } else {
            return 0;
        }
    }
}
