<?php
  $url = "https://www.uni-muenster.de/BAI/weather/";
   
  if (! $input = @file_get_contents($url))
  {
    $wetter = "Konnte Wetterdaten nicht laden";
  }
  else
  { 
    preg_match('~<table class="weather">.*?Messzeit.*?(\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}).*?Lufttemperatur.*?(-?\d+\.?\d*).*?Relative Luftfeuchte.*?(\d+).*?Luftdruck.*?(\d+\.?\d*).*?entspricht Windstärke.*?Beaufort (\d+).*?Windrichtung.*?aus (\w+).*?</table>~si', $input, $wetterdaten);
    
    // Prüfe ob der Regex erfolgreich war
    if (count($wetterdaten) >= 7) {
      // Extrahiere nur die Uhrzeit aus dem Datum
      $datum_zeit = $wetterdaten[1];
      preg_match('/(\d{2}:\d{2})/', $datum_zeit, $zeit_match);
      $uhrzeit = isset($zeit_match[1]) ? $zeit_match[1] : "--:--";
      
      $temperatur   = $wetterdaten[2];
      $luftfeuchte  = $wetterdaten[3];
      $luftdruck    = $wetterdaten[4];
      $windstaerke  = $wetterdaten[5];
      $windrichtung = $wetterdaten[6];
    } else {
      // Fallback-Werte wenn Regex fehlschlägt
      $uhrzeit      = "--:--";
      $temperatur   = "--";
      $luftfeuchte  = "--";
      $windstaerke  = "--";
      $windrichtung = "--";
      $luftdruck    = "--";
    }
  }
  
  echo"<strong>Aktuelles Wetter vom Dach des GEO1</strong> (Stand $uhrzeit Uhr)<br />Temperatur: <strong>$temperatur&nbsp;°C</strong> &nbsp;&bull;&nbsp; Luftfeuchte: <strong>$luftfeuchte&nbsp;%</strong> &nbsp;&bull;&nbsp; Wind: <strong>$windstaerke&nbsp;bft aus $windrichtung</strong>";
?>