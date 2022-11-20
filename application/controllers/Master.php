<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('MasterModel', 'msm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Data | StarJaya'
        ];
        $this->load->view('master/master', $data);
    }

    public function loadData()
    {
        $data = [
            'customer' => $this->msm->loadData()[0],
            'amountCustomer' => $this->msm->loadData()[1],
            'market' => $this->msm->loadData()[2],
            'amountMarket' => $this->msm->loadData()[3]
        ];
        $this->load->view('master/ajax-data', $data);
    }

    public function save()
    {
        $result = $this->msm->save();

        echo json_encode($result);
    }

    public function edit()
    {
        $result = $this->msm->edit();

        echo json_encode($result);
    }
}
