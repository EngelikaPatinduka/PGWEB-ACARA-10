<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Edit Data Sekolah</title>
</head>
<body>

<h2>Form Edit Data Sekolah</h2>

<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "acara9_pgweb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM sekolah WHERE id = $id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<form action='edit/index.php' onsubmit='validateForm(event)' method='post'>";
    
    while($row = $result->fetch_assoc()) {
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'><br>";
        
        echo "<label for='nama_sekolah'>Nama Sekolah:</label><br>";
        echo "<input type='text' id='nama_sekolah' name='nama_sekolah' value='" . htmlspecialchars($row['nama_sekolah']) . "' required><br><br>";
        
        echo "<label for='npsn'>NPSN:</label><br>";
        echo "<input type='text' id='npsn' name='npsn' value='" . htmlspecialchars($row['npsn']) . "' required><br><br>";
        
        echo "<label for='bentuk_pen'>Bentuk Pendidikan:</label><br>";
        echo "<input type='text' id='bentuk_pen' name='bentuk_pen' value='" . htmlspecialchars($row['bentuk_pen']) . "' required><br><br>";
        
        echo "<label for='status'>Status:</label><br>";
        echo "<input type='text' id='status' name='status' value='" . htmlspecialchars($row['status']) . "' required><br><br>";
        
        echo "<label for='alamat'>Alamat:</label><br>";
        echo "<input type='text' id='alamat' name='alamat' value='" . htmlspecialchars($row['alamat']) . "' required><br><br>";
        
        echo "<label for='kelurahan'>Kelurahan:</label><br>";
        echo "<input type='text' id='kelurahan' name='kelurahan' value='" . htmlspecialchars($row['kelurahan']) . "' required><br><br>";
        
        echo "<label for='kecamatan'>Kecamatan:</label><br>";
        echo "<input type='text' id='kecamatan' name='kecamatan' value='" . htmlspecialchars($row['kecamatan']) . "' required><br><br>";
        
        echo "<label for='latitude'>Latitude:</label><br>";
        echo "<input type='text' id='latitude' name='latitude' value='" . htmlspecialchars($row['latitude']) . "' required><br><br>";
        
        echo "<label for='longitude'>Longitude:</label><br>";
        echo "<input type='text' id='longitude' name='longitude' value='" . htmlspecialchars($row['longitude']) . "' required><br><br>";
    }
    
    echo "<input type='submit' value='Submit'>";
    echo "</form>";
} else {
    echo "Data tidak ditemukan.";
}

$conn->close();
?>

<p id="informasi"></p>

<script>
function validateForm(event) {
    let lat = document.getElementById("latitude").value;
    let long = document.getElementById("longitude").value;
    let text = "";
    
    if (isNaN(lat) || isNaN(long)) {
        text = "Latitude dan Longitude harus berupa angka";
        event.preventDefault();
    }
    
    document.getElementById("informasi").innerHTML = text;
}
</script>

</body>
</html>