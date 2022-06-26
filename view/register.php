<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */
$nama = $_POST['nama'];
$email = $_POST[ 'email'];
$password = $_POST['password'];
$nohp = $_POST['nohp'];
$nik = $_POST['nik'];

try{

    if($nama != '' && $email != '' && $password != '' && $nohp != '' && $nik != ''){

        $query = "INSERT INTO akun (nama, email, password, nohp, nik) 
              VALUES ('$nama','$email','$password','$nohp','$nik')";

        $execute = $connection->query($query);
        $response = [];


        if ($execute) {
            $response['status'] = 'success';
            $response['message'] = 'Berhasil Mendaftar Account';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Gagal Mendaftar Account';
        }

    } else {
        $response['status']= 'failed';
        $response['message']= 'Input tidak boleh kosong';
    }
}catch(Exception $exception){
    $response['status']= 'failed';
    $response['message'] = $exception->getMessage();
}
header("Content-Type: application/json; charset=UTF-8");
$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;