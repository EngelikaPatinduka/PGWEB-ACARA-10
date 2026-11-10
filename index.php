<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "acara9_pgweb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sekolah";
$result = $conn->query($sql);

$data_sekolah = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_sekolah[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web GIS Sebaran Sekolah</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        #map {
            height: 480px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            margin-bottom: 25px;
        }

        .btn-input {
            display: inline-block;
            padding: 9px 18px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .btn-input:hover {
            background-color: #218838;
        }

        .table-responsive {
            overflow-x: auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        th {
            background-color: #007bff;
            color: white;
            white-space: nowrap;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        .action-link {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .btn-edit { background-color: #ffc107; color: #212529; }
        .btn-delete { background-color: #dc3545; }
        .btn-zoom { background-color: #17a2b8; }

        .btn-edit:hover { background-color: #e0a800; }
        .btn-delete:hover { background-color: #c82333; }
        .btn-zoom:hover { background-color: #138496; }

        .custom-school-icon {
            border: 1.5px solid #007bff;
            border-radius: 50%;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            object-fit: cover;
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Web GIS Sebaran Sekolah</h2>
        
        <div id="map"></div>

        <a href="input/index.html" class="btn-input">+ Tambah Data Sekolah</a>

        <h3>Data Tabel Sekolah</h3>
        <div class="table-responsive">
            <?php if (!empty($data_sekolah)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Sekolah</th>
                            <th>NPSN</th>
                            <th>Bentuk Pen</th>
                            <th>Status</th>
                            <th>Alamat</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th colspan="3" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_sekolah as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id"]); ?></td>
                                <td><?= htmlspecialchars($row["nama_sekolah"]); ?></td>
                                <td><?= htmlspecialchars($row["npsn"]); ?></td>
                                <td><?= htmlspecialchars($row["bentuk_pen"]); ?></td>
                                <td><?= htmlspecialchars($row["status"]); ?></td>
                                <td><?= htmlspecialchars($row["alamat"]); ?></td>
                                <td><?= htmlspecialchars($row["kelurahan"]); ?></td>
                                <td><?= htmlspecialchars($row["kecamatan"]); ?></td>
                                <td><?= htmlspecialchars($row["latitude"]); ?></td>
                                <td><?= htmlspecialchars($row["longitude"]); ?></td>
                                <td><button class="action-link btn-zoom" onclick="zoomToMarker(<?= $row['latitude']; ?>, <?= $row['longitude']; ?>)">Zoom</button></td>
                                <td><a href="edit.php?id=<?= $row["id"]; ?>" class="action-link btn-edit">Edit</a></td>
                                <td><a href="delete.php?id=<?= $row["id"]; ?>" class="action-link btn-delete" onclick="return confirm('Yakin hapus data?');">Hapus</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="padding: 15px; background: white;">Tidak ada data sekolah yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

    <script>
        var map = L.map('map').setView([-6.85, 107.60], 12);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Badan Informasi Geospasial'
        });

        osm.addTo(map);

        var sekolahLayer = L.layerGroup().addTo(map);
        var drawnItems = new L.FeatureGroup().addTo(map);

        var dbSekolah = <?php echo json_encode($data_sekolah); ?>;

        dbSekolah.forEach(function(sekolah) {
            var lat = parseFloat(sekolah.latitude);
            var lng = parseFloat(sekolah.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                var gambarUrl = (sekolah.foto && sekolah.foto !== "") 
                    ? "uploads/" + sekolah.foto 
                    : "https://cdn-icons-png.flaticon.com/512/167/167707.png";

                var schoolIcon = L.icon({
                    iconUrl: gambarUrl,
                    iconSize: [26, 26],
                    iconAnchor: [13, 13],
                    popupAnchor: [0, -13],
                    className: 'custom-school-icon'
                });

                var popupContent = `
                    <div style="font-size: 13px; text-align: center;">
                        <img src="${gambarUrl}" style="width: 100%; max-width: 180px; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;"><br>
                        <b style="font-size: 14px; color: #007bff;">${sekolah.nama_sekolah}</b><br>
                        <b>NPSN:</b> ${sekolah.npsn}<br>
                        <b>Bentuk:</b> ${sekolah.bentuk_pen} (${sekolah.status})<br>
                        <b>Alamat:</b> ${sekolah.alamat}, Kel. ${sekolah.kelurahan}, Kec. ${sekolah.kecamatan}
                    </div>
                `;

                var marker = L.marker([lat, lng], { icon: schoolIcon }).bindPopup(popupContent);
                marker.addTo(sekolahLayer);
            }
        });

        var baseMaps = {
            "OpenStreetMap": osm,
            "Esri World Imagery": Esri_WorldImagery,
            "Rupa Bumi Indonesia": rupabumiindonesia
        };

        var overlayMaps = {
            "Titik Sekolah": sekolahLayer,
            "Hasil Pengukuran / Digitasi": drawnItems
        };

        L.control.layers(baseMaps, overlayMaps, { collapsed: false }).addTo(map);

        L.control.scale({
            maxWidth: 150,
            metric: true,
            imperial: false,
            position: 'bottomright'
        }).addTo(map);

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                polygon: true,
                polyline: true,
                rectangle: true,
                circle: true,
                marker: true,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function (event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);
        });

        function zoomToMarker(lat, lng) {
            map.setView([lat, lng], 18);
            sekolahLayer.eachLayer(function(marker) {
                if (marker.getLatLng().lat === lat && marker.getLatLng().lng === lng) {
                    marker.openPopup();
                }
            });
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>