<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$sql = 'update tasks set status = "done" where id = :id';
$id = $_GET['id'];

$stmt = $dbh->prepare($sql);
$stmt->bindparam(':id', $id);
$stmt->execute();

header('Location: index.php');
exit;

?>