<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */
if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
    http_response_code(400);
    $reply['error'] = 'DELETE method required';
    echo json_encode($reply);
    exit();
}

/**
 * Get input data from RAW data
 */
$data = file_get_contents('php://input');
$res = [];
parse_str($data, $res);
$id_masjid = $res['id_masjid'] ?? 0;
/**
 * Validation int value
 */
$id_masjidFilter = filter_var($id_masjid, FILTER_VALIDATE_INT);
/**
 * Validation empty fields
 */
$isValidated = true;
if($id_masjidFilter === false){
    $reply['error'] = "ID masjid harus format INT";
    $isValidated = false;
}
/*
 * Jika filter gagal
 */
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 *
 * Cek apakah ID masjid tersedia
 */
try{
    $queryCheck = "SELECT * FROM masjid where id_masjid = :id_masjid";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_masjid', $id_masjid);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan '.$id_masjid;
        echo json_encode($reply);
        http_response_code(400);
        exit(0);
    }
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/**
 * Hapus data
 */
try{
    $queryCheck = "DELETE FROM masjid where id_masjid = :id_masjid";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_masjid', $id_masjid);
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/*
 * Send output
 */
header("Content-Type: application/json; charset=UTF-8");
$reply['berhasil hapus masjid'] = true ;
echo json_encode($reply);