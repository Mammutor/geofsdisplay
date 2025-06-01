<?php
// PHP-Fehlerprotokollierung und -anzeige erzwingen
error_reporting(E_ALL);        // Alle Fehler und Warnungen anzeigen
ini_set('display_errors', 1);  // Fehler direkt im Browser anzeigen
ini_set('display_startup_errors', 1); // Fehler beim PHP-Start anzeigen
ini_set('log_errors', 1);      // Fehler in die Log-Datei schreiben
ini_set('error_log', '/proc/self/fd/2'); // Fehler in den Standard-Error-Stream schreiben (für Docker logs)

// Header setzen
header('Content-Type: image/gif');

// GIFEncoder-Klasse einbinden (bitte achte darauf, dass diese Datei im gleichen Ordner liegt!)
include('GIFEncoder.class.php');

// HTTP-Kontext mit User-Agent
$options = array('http' => array(
    'method' => "GET",
    'header' => "User-Agent: geofsdisplay v1.0\r\n"
));
$context = stream_context_create($options);

// Daten vom WetterOnline-Server holen
$apiUrl = 'https://tiles.wo-cloud.com/metadata?lg=wr&period=periodCurrentLowRes';
$dataJson = @file_get_contents($apiUrl, false, $context);
if ($dataJson === false) {
    $error = error_get_last();
    die('Fehler beim Laden der Wetterdaten von ' . $apiUrl . '. Details: ' . (isset($error['message']) ? $error['message'] : 'Unbekannter Fehler.'));
}
$data = json_decode($dataJson);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Fehler beim Dekodieren der JSON-Daten: ' . json_last_error_msg());
}

if (!isset($data->liveid) || !isset($data->timesteps) || empty($data->timesteps)) { // Hinzugefügt: empty($data->timesteps)
    die('Ungültiges Datenformat von der API. Erwartete "liveid" oder "timesteps" fehlen oder "timesteps" ist leer.');
}

// Zeitzonen-Offset korrekt extrahieren
$liveidParts = explode('-', $data->liveid);
$timezoneoffset = isset($liveidParts[2]) ? intval($liveidParts[2]) : 0;

$frames = [];
$dauer = [];

foreach ($data->timesteps as $step) {
    $frameCounter = 0;
    $frameCounter++;
    // URL-Zusammenstellung für das Bild
    $tiles = 'topo|1;;0;0|wetterradar/prozess/tiles/geolayer/rasterimages/rr_topography/v1/ZL8/512/132_84.jpg$r|2;;0;0;false' .
        '|' . $step->layers->europe->rain->ptypPath . $step->layers->europe->rain->path . '/' . implode('/', $step->layers->europe->rain->timePath) . '/ZL7/522/sprite/66_42.png' .
        ';' . $step->layers->global->rain->ptypPath . $step->layers->global->rain->path . '/' . implode('/', $step->layers->global->rain->timePath) . '/ZL7/522/border/66_42.png$i' .
        '|1;;0;0|geo/prozess/karten/produktkarten/wetterradar/generate/rasterTiles/rr_geooverlay/v2/ZL8/512/132_84.png';

    $url = 'https://tiles.wo-cloud.com/composite?format=png&lg=rr&tiles=' . urlencode(base64_encode($tiles));

    error_log("Versuche Bild $frameCounter abzurufen von: $url");
    $datei = @file_get_contents($url, false, $context);
    if ($datei === false) {
        $error = error_get_last();
        error_log("FEHLER: Beim Abrufen des Bildes $frameCounter von: $url. Details: " . (isset($error['message']) ? $error['message'] : 'Unbekannter Fehler.'));
        continue; // Wichtig: Mit continue geht es zum nächsten Frame
    }

    $image = @imagecreatefromstring($datei);
    if ($image === false) {
        error_log("FEHLER: Beim Erzeugen des Bildes $frameCounter aus den Daten (vermutlich kein gültiges Bildformat). URL: $url");
        continue;
    }

    $cropped = @imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 512, 'height' => 365]);
    if ($cropped === false) {
        error_log("FEHLER: Beim Croppen des Bildes $frameCounter. URL: $url");
        imagedestroy($image);
        continue;
    }

    // Zeit extrahieren und darstellen
    $hour = ((int)$step->layers->europe->rain->timePath[3] + $timezoneoffset) % 24;
    $minute = $step->layers->europe->rain->timePath[4];
    $time = sprintf('%02d:%s', $hour, $minute);

    $textcolor = imagecolorallocate($cropped, 0, 0, 0); // Schwarz
    imagestring($cropped, 5, 342, 178, $time, $textcolor); // Zeitstempel einfügen

    // GIF-Frame erzeugen
    ob_start();
    imagegif($cropped);
    $frames[] = ob_get_clean();
    $dauer[] = 100; // Dauer pro Frame (100 = 1 Sekunde)

    // Speicher aufräumen
    imagedestroy($image);
    imagedestroy($cropped);
}

// Letzten Frame länger anzeigen (Ende besser sichtbar)
if (!empty($frames)) {
    $frames[] = end($frames);
    $dauer[] = 200;
} else {
    die("Keine gültigen Frames erzeugt. Bitte überprüfen Sie die Logs für weitere Details."); // Diese Meldung ist jetzt sehr wichtig
}

// GIF erzeugen
$gif = new GIFEncoder($frames, $dauer, 0, 2, 0, 0, 0, 'bin');
echo $gif->GetAnimation();
?>
