<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */

/*
 * Validate http method
 */
if($_SERVER['REQUEST_METHOD'] !== 'PATCH'){
    header('Content-Type: application/json');
    http_response_code(400);
    $reply['error'] = 'PATCH method required';
    echo json_encode($reply);
    exit();
}
/**
 * Get input data PATCH
 */
$formData = [];
parse_str(file_get_contents('php://input'), $formData);

$id_masjid = $formData['id_masjid'] ?? 0;
$nama_masjid = $formData['nama_masjid'] ?? '';

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
if(empty($id_masjid)){
    $reply['error'] = 'ID masjid harus diisi';
    $isValidated = false;
}
if(empty($nama_masjid)){
    $reply['error'] = 'Nama masjid harus diisi';
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
 * METHOD OK
 * Validation OK
 * Check if data is exist
 */
try{
    $queryCheck = "SELECT * FROM masjid where id_masjid = :id_masjid";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_masjid', $id_masjidFilter);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan '.$id_masjidFilter;
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
 * Prepare query
 */
try{
    $fields = [];
    $query = "UPDATE masjid SET nama_masjid = :nama_masjid WHERE id_masjid = :id_masjid";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_masjid", $id_masjid);
    $statement->bindValue(":nama_masjid", $nama_masjid);
    /**
     * Execute query
     */
    $isOk = $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * If not OK, add error info
 * HTTP Status code 400: Bad request
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#client_error_responses
 */
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}

/**
 * Show output to client
 */
header("Content-Type: application/json; charset=UTF-8");
$reply['status'] = $isOk;
echo json_encode($reply);
