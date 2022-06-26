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
$id_akun = $_POST['id_akun'] ?? 0;
$id_masjid = $_POST['id_masjid'] ?? 0;
$username = $_POST['username'] ?? '';
$nominal = $_POST['nominal'] ?? 0;
$note = $_POST['note'] ?? '';

$id_akunFilter = filter_var($id_akun, FILTER_VALIDATE_INT);
$id_masjidFilter = filter_var($id_masjid, FILTER_VALIDATE_INT);
$nominalFilter = filter_var($nominal, FILTER_VALIDATE_INT);

$isValidated = true;
if(empty($id_akun)){
    $reply['error'] = 'id akun harus diisi';
    $isValidated = false;
}
if(empty($id_masjid)){
    $reply['error'] = 'id masjid harus diisi';
    $isValidated = false;
}
if(empty($username)){
    $reply['error'] = 'username harus diisi';
    $isValidated = false;
}
if(empty($nominal)){
    $reply['error'] = 'nominal harus diisi';
    $isValidated = false;
}
if(empty($note)){
    $reply['error'] = 'note harus diisi';
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
    $query = "INSERT INTO histori (id_akun, id_masjid, username, nominal, note) VALUES (:id_akun, :id_masjid, :username, :nominal, :note)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_akun", $id_akun);
    $statement->bindValue(":id_masjid", $id_masjid);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":nominal", $nominal);
    $statement->bindValue(":note", $note);
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
$getResult = "SELECT * FROM histori WHERE id_histori = :id_histori";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_histori', $lastId);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);


/**
 * Show output to client
 * Set status info true
 */
$reply['data'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);