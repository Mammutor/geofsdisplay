<?php
  $url = "https://www.uni-muenster.de/BAI/data/weather2.txt";
   
  if (! $input = @file_get_contents($url))
  {
    $wetter = "Konnte Wetterdaten nicht laden";
  }
  else
  { 
    // Reg-Ex für Wetterdaten
    preg_match('~<table class="news_weather".*?<th>Lokalzeit</th>.*?<td>(\d{2}:\d{2})</td>.*?<th>Temperatur</th>.*?<td>(-?\d+\.?\d*).*?<th>Luftfeuchte</th>.*?<td>(\d+).*?<th>Windst&auml;rke</th>.*?<br />(\d+\.?\d*).*?km.*?<th>Windrichtung</th>.*?aus (\w+).*?<th>Luftdruck</th>.*?<td>(\d+\.?\d*).*?</table>~si', $input, $wetterdaten);
    
    // Prüfe ob der Regex erfolgreich war
    if (count($wetterdaten) >= 7) {
      // Lokalzeit ist bereits im Format HH:MM
      $uhrzeit = $wetterdaten[1];
      
      $temperatur   = $wetterdaten[2];
      $luftfeuchte  = $wetterdaten[3];
      $windgeschwindigkeit = $wetterdaten[4];
      $windrichtung = $wetterdaten[5];
      $luftdruck    = $wetterdaten[6];
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