<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ordering extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataModel', 'dm');
        $this->load->model('OrderingModel', 'om');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pemesanan',
            'customer' => $this->om->customer()
        ];
        $this->load->view('ordering/ordering', $data);
    }

    public function loadData()
    {
        $data = [
            'datas' => $this->om->loadData()
        ];
        $this->load->view('ordering/ajax-data', $data);
    }

    public function done($id)
    {
        $id = encrypt_url($id);

        redirect('ordering/completeorder/' . $id);
    }

    public function completeOrder($id)
    {
        $id = decrypt_url($id);
        $data = [
            'title' => 'Selesaikan Pesanan',
            'id' => $id
        ];
        $this->load->view('ordering/ordering-complete', $data);
    }

    public function loaddatacomplete()
    {
        $id = $this->input->post('id', true);
        $data = [
            'status' => $this->om->detailOrder($id)['status'],
            'order' => $id,
            'customer' => $this->om->getCustomerName($id)[0],
            'data' => $this->om->detailOrder($id)['data'],
            'statusOrder' => $this->om->getCustomerName($id)[1]
        ];
        $this->load->view('ordering/ajax-ordering-complete', $data);
    }

    public function detail()
    {
        $invoice = $this->input->post('invoice', true);
        $data = [
            'status' => $this->om->detailOrder($invoice)['status'],
            'data' => $this->om->detailOrder($invoice)['data'],
            'amount' => $this->om->detailOrder($invoice)['amount'],
            'item' => $this->om->detailOrder($invoice)['item']
        ];
        $this->load->view('ordering/ajax-detail', $data);
    }

    public function getQty()
    {
        $result = $this->om->getQty();

        echo json_encode($result);
    }

    public function update()
    {
        $result = $this->om->update();

        echo json_encode($result);
    }

    public function approve()
    {
        $result = $this->om->approve();

        echo json_encode($result);
    }

    public function deliver()
    {
        $result = $this->om->deliver();

        echo json_encode($result);
    }

    public function cancel()
    {
        $result = $this->om->cancel();

        echo json_encode($result);
    }

    public function saveOrder()
    {
        $result = $this->om->saveOrder();

        echo json_encode($result);
    }

    public function deletetransaction()
    {
        $result = $this->om->deletetransaction();

        echo json_encode($result);
    }

    public function print()
    {
        // $id = decrypt_url($id);
        $id = $this->input->post('id', true);
        $data = [
            'title' => 'Cetak Faktur',
            'data' => $this->om->dataprint($id)
        ];
        $this->load->view('print/invoice', $data);
    }
}
