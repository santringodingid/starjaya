<?php

function CekLogin()
{
    $ci = get_instance();

    if (!$ci->session->userdata('user_id')) {
        redirect('auth');
    }
}

function CekLoginAkses()
{
    $ci = get_instance();

    $id = $ci->session->userdata('user_id');
    $role = $ci->session->userdata('role');

    if (!$id) {
        redirect('auth');
    } else {
        $ci->load->model('MenuModel', 'mm');
        $url      = $ci->uri->segment(1);
        $idMenu = $ci->mm->getURL($url);

        $cekAkses = $ci->mm->cekUserMenu($idMenu, $role);

        if ($cekAkses <= 0 && $role != 'DEV') {
            redirect('block');
        }
    }
}

function getMenu()
{
    $ci = get_instance();
    $ci->load->model('menuModel');
    $role = $ci->session->userdata('role');

    return $ci->menuModel->getMenu($role);
}

function periodDisplay()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->periodDisplay();
}

function getHijri()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->getHijri();
}

function getHijriManual($date)
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->getHijriManual($date);
}

function getHijriExplode()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    $result = $ci->DataModel->getHijri();
    return explode('-', $result);
}

function getMasehiExplode()
{
    $date = date('Y-m-d');
    return explode('-', $date);
}

function setTimeDiff($date)
{
    $time = date('2022-10-23 08:00:00');

    $selisih = strtotime($time) - strtotime($date);

    if ($selisih >= 1) {
        $hasil = 0; //DISIPLIN
    } else {
        $hasil = 1; //TELAT
    }

    $awal = date_create($time);
    $akhir = date_create($date); // waktu sekarang
    $diff  = date_diff($awal, $akhir);

    $tahun = $diff->y;
    $bulan = $diff->m;
    $hari = $diff->d;
    $jam = $diff->h;
    $menit = $diff->i;
    $detik = $diff->s;

    if ($tahun != 0) {
        $t = $tahun . ' tahun';
    } else {
        $t = '';
    }

    if ($bulan != 0) {
        $b = $bulan . ' bulan';
    } else {
        $b = '';
    }

    if ($hari != 0) {
        $h = $hari . ' hari';
    } else {
        $h = '';
    }

    if ($jam != 0) {
        $j = $jam . ' jam';
    } else {
        $j = '';
    }

    if ($menit != 0) {
        $m = $menit . ' menit';
    } else {
        $m = '';
    }

    if ($detik != 0) {
        $d = $detik . ' detik';
    } else {
        $d = '';
    }

    if ($hasil == 0) {
        return 'Disiplin';
    } else {
        return 'Terlamabat ' . $t . ' ' . $b . ' ' . $h . ' ' . $j . ' ' . $m;
    }
}
