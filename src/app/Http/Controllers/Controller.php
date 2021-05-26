<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponse;
    public $validationError = "Validasi Error.";
    public $loginSukses = "Login berhasil";
    public $loginGagal = "Login gagal";
    public $dataFound = "Data ditemukan.";
    public $dataNotFound = "Data tidak ditemukan.";
    public $addSuccess = "Data berhasil ditambahkan.";
    public $addFailed = "Data gagal ditambahkan";
    public $updateSuccess = "Data berhasil diperbarui.";
    public $deleteSuccess = "Data berhasil dihapus.";
    public $unknownMode = "Mode tidak ditemukan.";
    public $saveSuccess = "Data berhasil disimpan";
    public $serviceError = "Oops Sepertinya ada masalah, mohon coba lagi.";

    public function index()
    {
        $data = [
            "message" => "Hai :D",
            "data" => []
        ];
        return $this->responseSukses($data);
    }

    function handleNull($array){
        $data_arr = json_decode(json_encode($array),true);
        $data = array_map(function($value) {
            return $value === NULL ? "" : $value;
         }, $data_arr);
        return $data;
    }

    function handleNullMultiDimensi($array){
        $data = json_decode(json_encode($array),true);
        array_walk_recursive($data, 'self::replaceNullValueWithEmptyString');
        return $data;
    }

    function replaceNullValueWithEmptyString(&$value) {
        return $value = $value === null ? "" : $value;
    }
}
