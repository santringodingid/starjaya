<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('ProductModel', 'pm');
        CekLoginAkses();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Barang',
            'categories' => $this->pm->categories(),
            'packages' => $this->pm->packages(),
            'units' => $this->pm->units(),
        ];
        $this->load->view('product/product', $data);
    }

    public function loadData()
    {
        $data = [
            'product' => $this->pm->loadData()[0],
            'amount' => $this->pm->loadData()[1]
        ];
        $this->load->view('product/ajax-data', $data);
    }

    public function print()
    {
        $data = [
            'title' => 'Print Out Data Produk',
            'data' => $this->pm->print()[0],
            'amount' => $this->pm->print()[1]
        ];
        $this->load->view('print/product', $data);
    }

    public function pdf()
    {
        // panggil library yang kita buat sebelumnya yang bernama pdfgenerator
        $this->load->library('pdfgenerator');

        // title dari pdf
        $data = [
            'title' => 'Data Produk',
            'data' => $this->pm->print()[0],
            'amount' => $this->pm->print()[1]
        ];
        // $this->load->view('product/pdf', $data);

        // filename dari pdf ketika didownload
        $file_pdf = 'daftar-produk-star-jaya';
        // setting paper
        $paper = 'A4';
        //orientasi paper potrait / landscape
        $orientation = "portrait";

        $html = $this->load->view('product/pdf', $data, true);
        //$this->load->view('registration/invoice',$data);	    

        // run dompdf
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function save()
    {
        $result = $this->pm->save();

        echo json_encode($result);
    }

    public function edit()
    {
        $result = $this->pm->edit();

        echo json_encode($result);
    }

    public function upload()
    {
        //AMBIL IMAGE SEBELUMNYA
        $oldImage = $this->pm->getImage();

        //AMBIL EKSTENSI IMAGE
        $nameFile = explode('.', $_FILES['image']['name']);
        //RENAME IMAGE SEBELUM DIUPLOAD
        $image = uniqid() . '-' . time() . '.' . end($nameFile);

        $config['upload_path'] = './assets/images/products';
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = $image;

        $upload = $this->pm->upload($image);
        if ($upload['status'] == 400) {
            $result = $upload;
        } else {
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $result = [
                    'status' => 400,
                    'message' => $this->upload->display_errors()
                ];
            } else {
                $pathImageOld = FCPATH . 'assets/images/products/' . $oldImage;
                if (file_exists($pathImageOld === TRUE)) {
                    unlink($pathImageOld);
                }
                $result = [
                    'status' => 200,
                    'message' => 'Image berhasil diupload'
                ];
            }

            echo json_encode($result);
        }
    }
}
