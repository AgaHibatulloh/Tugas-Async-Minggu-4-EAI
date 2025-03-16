<?php
include 'config.php';

function getWeather($city) {
    $apiKey = getApiKey();
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    $response = file_get_contents($apiUrl);
    return json_decode($response, true);
}

$city = isset($_GET['city']) ? $_GET['city'] : 'Jakarta';
$data = getWeather($city);

if ($data && isset($data['main'])) {
    $temperature = $data['main']['temp'];
    $feels_like = $data['main']['feels_like'];
    $temp_min = $data['main']['temp_min'];
    $temp_max = $data['main']['temp_max'];
    $pressure = $data['main']['pressure'];
    $humidity = $data['main']['humidity'];
    $wind_speed = $data['wind']['speed'];
    $wind_deg = $data['wind']['deg'];
    $clouds = $data['clouds']['all'];
    $visibility = $data['visibility'];
    $lat = $data['coord']['lat'];
    $lon = $data['coord']['lon'];
    $weather = ucfirst($data['weather'][0]['description']);
    $icon = $data['weather'][0]['icon'];
    $country = $data['sys']['country'];
    $sunrise = date('H:i:s', $data['sys']['sunrise']);
    $sunset = date('H:i:s', $data['sys']['sunset']);
} else {
    $temperature = $feels_like = $temp_min = $temp_max = $pressure = $humidity = $wind_speed = $wind_deg = $clouds = $visibility = "Data tidak tersedia";
    $lat = $lon = "0.0";
    $weather = "Tidak ada data";
    $icon = "01d";
    $country = "N/A";
    $sunrise = $sunset = "00:00:00";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaca di <?php echo $city; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .card {
            width: 400px;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            background: white;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .weather-icon {
            width: 100px;
            margin-bottom: 10px;
        }
        .temperature {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .details {
            font-size: 1rem;
        }
        .footer {
            margin-top: 10px;
            font-size: 0.8rem;
            color: gray;
        }
        .input-group {
            width: 400px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="input-group">
        <input type="text" id="cityInput" class="form-control" placeholder="Masukkan nama kota..." value="<?php echo $city; ?>">
        <button class="btn btn-primary" onclick="getWeather()">Cari</button>
    </div>

    <div class="card" id="weatherCard">
        <h2 id="cityName"><?php echo $city . ", " . $country; ?></h2>
        <p>Koordinat: <?php echo $lat . "°N, " . $lon . "°E"; ?></p>
        <img src="https://openweathermap.org/img/wn/<?php echo $icon; ?>@2x.png" class="weather-icon" id="weatherIcon">
        <p class="temperature" id="temperature"><?php echo $temperature; ?>°C</p>
        <p class="details" id="weather"><?php echo $weather; ?></p>
        <p class="details"><i class="fas fa-thermometer"></i> Terasa Seperti: <?php echo $feels_like; ?>°C</p>
        <p class="details"><i class="fas fa-temperature-low"></i> Min: <?php echo $temp_min; ?>°C | Max: <?php echo $temp_max; ?>°C</p>
        <p class="details"><i class="fas fa-compress-alt"></i> Tekanan: <?php echo $pressure; ?> hPa</p>
        <p class="details"><i class="fas fa-tint"></i> Kelembapan: <?php echo $humidity; ?>%</p>
        <p class="details"><i class="fas fa-wind"></i> Angin: <?php echo $wind_speed; ?> m/s, <?php echo $wind_deg; ?>°</p>
        <p class="details"><i class="fas fa-cloud"></i> Awan: <?php echo $clouds; ?>%</p>
        <p class="details"><i class="fas fa-eye"></i> Visibilitas: <?php echo $visibility; ?> meter</p>
        <p class="details"><i class="fas fa-sun"></i> Matahari Terbit: <?php echo $sunrise; ?></p>
        <p class="details"><i class="fas fa-moon"></i> Matahari Terbenam: <?php echo $sunset; ?></p>
        <div class="footer">Data dari OpenWeather</div>
    </div>

    <script>
        function getWeather() {
            let city = document.getElementById("cityInput").value;
            if (city.trim() === "") return;
            
            fetch(`weather.php?city=${city}`)
                .then(response => response.text())
                .then(data => {
                    document.body.innerHTML = data;
                })
                .catch(error => console.log("Error:", error));
        }
    </script>

</body>
</html>
