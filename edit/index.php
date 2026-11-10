<?php
$id           = $_POST['id'];
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

$sql = "UPDATE sekolah SET 
            nama_sekolah = '$nama_sekolah',
            npsn = '$npsn',
            bentuk_pen = '$bentuk_pen',
            status = '$status',
            alamat = '$alamat',
            kelurahan = '$kelurahan',
            kecamatan = '$kecamatan',
            latitude = '$latitude',
            longitude = '$longitude'
        WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Record edited successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: ../index.php");
exit();
?>