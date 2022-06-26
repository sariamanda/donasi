<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
/**
 * Get input data POST
 */
$img = $_POST['img'] ?? '';
$nama_masjid = $_POST['nama_masjid'] ?? '';
$tahun_berdiri = $_POST['tahun_berdiri'] ?? 0;
$takmir = $_POST['takmir'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

$tahun_berdiriFilter = filter_var($tahun_berdiri, FILTER_VALIDATE_INT);

$isValidated = true;
if(empty($img)){
    $reply['error'] = 'gambar masjid harus diisi';
    $isValidated = false;
}
if(empty($nama_masjid)){
    $reply['error'] = 'nama masjid harus diisi';
    $isValidated = false;
}
if(empty($tahun_berdiri)){
    $reply['error'] = 'tahun berdiri masjid harus diisi';
    $isValidated = false;
}
if(empty($takmir)){
    $reply['error'] = 'nama takmir masjid harus diisi';
    $isValidated = false;
}
if(empty($deskripsi)){
    $reply['error'] = 'deskripsi masjid harus diisi';
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
 * Method OK
 * Validation OK
 * Prepare query
 */
try{
    $query = "INSERT INTO masjid (img, nama_masjid, tahun_berdiri, takmir, deskripsi) VALUES (:img, :nama_masjid, :tahun_berdiri, :takmir, :deskripsi)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":img", $img);
    $statement->bindValue(":nama_masjid", $nama_masjid);
    $statement->bindValue(":tahun_berdiri", $tahun_berdiri);
    $statement->bindValue(":takmir", $takmir);
    $statement->bindValue(":deskripsi", $deskripsi);
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

/*
 * Get last data
 */
$lastId = $connection->lastInsertId();
$getResult = "SELECT * FROM masjid WHERE id_masjid = :id_masjid";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_masjid', $lastId);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);


/**
 * Show output to client
 * Set status info true
 */
$reply['data'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);