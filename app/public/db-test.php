<?php
include __DIR__.'/autoryzacja.php';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die('connection failed: ' . mysqli_connect_error());
}

echo 'connection successful<br>';

$res = mysqli_query($conn, 'SELECT NOW() AS now');
$row = mysqli_fetch_assoc($res);
echo 'server time: ' . htmlspecialchars($row['now']) . '<br>';

mysqli_close($conn);
