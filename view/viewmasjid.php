<?php
require_once '../koneksi.php';
/**
 * @var $connection PDO
 */
try{
    $statement = $connection->prepare("select * from masjid");
    $isOk = $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $reply['data'] = $results;
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}
/*
 * Query OK
 * set status == true
 * Output JSON
 */
$reply['status'] = true;
header("Content-Type: application/json; charset=UTF-8");
$json = json_encode($reply, JSON_PRETTY_PRINT);
echo json_encode($reply);