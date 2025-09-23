<?php
  $url = "https://www.uni-muenster.de/BAI/weather/";
   
  if (! $input = @file_get_contents($url))
  {
    $wetter = "Konnte Wetterdaten nicht laden";
  }
  else
  { 
    preg_match('~<table class="weather">.*?Messzeit.*?(\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}).*?Lufttemperatur.*?class="data tab4".*?(-?\d+\.?\d*).*?Relative Luftfeuchte.*?class="data tab3".*?(\d+).*?Luftdruck.*?class="data tab4".*?(\d+\.?\d*).*?Windgeschwindigkeit.*?class="data tab3".*?m/s.*?(\d+\.?\d*).*?km/h.*?Windrichtung.*?aus (\w+).*?</table>~si', $input, $wetterdaten);
    
    // Prüfe ob der Regex erfolgreich war
    if (count($wetterdaten) >= 7) {
      // Extrahiere nur die Uhrzeit aus dem Datum
      $datum_zeit = $wetterdaten[1];
      preg_match('/(\d{2}:\d{2})/', $datum_zeit, $zeit_match);
      $uhrzeit = isset($zeit_match[1]) ? $zeit_match[1] : "--:--";
      
      $temperatur   = $wetterdaten[2];
      $luftfeuchte  = $wetterdaten[3];
      $luftdruck    = $wetterdaten[4];
      $windgeschwindigkeit = $wetterdaten[5];
      $windrichtung = $wetterdaten[6];
    } else {
      // Fallback-Werte wenn Regex fehlschlägt
      $uhrzeit      = "--:--";
      $temperatur   = "--";
      $luftfeuchte  = "--";
      $windgeschwindigkeit = "--";
      $windrichtung = "--";
      $luftdruck    = "--";
    }
  }
  
  echo"<strong>Aktuelles Wetter vom Dach des GEO1</strong> (Stand $uhrzeit Uhr)<br />Temperatur: <strong>$temperatur&nbsp;°C</strong> &nbsp;&bull;&nbsp; Luftfeuchte: <strong>$luftfeuchte&nbsp;%</strong> &nbsp;&bull;&nbsp; Wind: <strong>$windgeschwindigkeit&nbsp;km/h aus $windrichtung</strong>";
?>