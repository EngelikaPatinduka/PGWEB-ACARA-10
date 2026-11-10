<?php
$nama_sekolah = $_POST['nama_sekolah'];
$npsn         = $_POST['npsn'];
$bentuk_pen   = $_POST['bentuk_pen'];
$status       = $_POST['status'];
$alamat       = $_POST['alamat'];
$kelurahan    = $_POST['kelurahan'];
$kecamatan    = $_POST['kecamatan'];
$latitude     = $_POST['latitude'];
$longitude    = $_POST['longitude'];

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "acara9_pgweb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO sekolah (nama_sekolah, npsn, bentuk_pen, status, alamat, kelurahan, kecamatan, latitude, longitude)
        VALUES ('$nama_sekolah', '$npsn', '$bentuk_pen', '$status', '$alamat', '$kelurahan', '$kecamatan', '$latitude', '$longitude')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: ../index.php");
exit();
?>