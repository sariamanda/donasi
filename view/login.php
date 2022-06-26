<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */
$email = $_POST[ 'email'];
$password = $_POST['password'];


try {

    if($email != '' && $password != '') {

        $query = "SELECT * FROM akun WHERE email = '$email' AND password = '$password'";

        $execute = $connection->query($query);
        $response = [];
        $execute->setFetchMode(PDO::FETCH_ASSOC);
        $row = $execute->fetchAll();
        if ($row){
            if (count($row) > 0 ) {
                $response['status'] = 'success';
                $response['message'] = 'Berhasil Login';
                $response['data'] = $row;
            }
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Email atau Password salah';
            $response['user'] = $row;
        }

    } else {
        $response['status'] = 'failed';
        $response['message'] = 'Input tidak boleh kosong';
    }

}catch(Exception $exception){
    $response['status'] = 'failed';
    $response['message'] = 'Gagal Login :' . $exception->getMessage();

}
header("Content-Type: application/json; charset=UTF-8");
$json = json_encode($response, JSON_PRETTY_PRINT);
echo $json;