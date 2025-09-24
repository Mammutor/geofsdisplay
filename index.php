<!DOCTYPE html>
<html lang="de">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>geofsdisplay</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" href="images/displaylogo.png" />
  <!--<link rel="apple-touch-startup-image" href="images/startupscreen.png" />-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.js"></script>
  <script src="./js/hammerjs-v2.0.6.min.js" defer></script>
  <script src="./js/index.js" defer></script>
  <script src="./js/displaycontent.js" defer></script>
  <script src="./js/ajaxcontent.js" defer></script>
  <script src="./js/plakate.js" defer></script>
  <script src="./js/eastereggs.js" defer></script>
</head>
<body>
<div id="container">
  
  <div id="content">
  
    <div id="header">
      <img src="images/geofspacman.png" id="geofspacman" class="fslogo" onclick="window.location.reload()">
      <img src="images/geoloek.png" id="geoloekbaum" class="fslogo" onclick="window.location.reload()">
      <div id="fahrplan"></div>
      <div id="uhr">hh:mm:ss</div>
    </div>
    
    <div id="main">
      <div id="news">
        <div class="latestNews">
          <h3 class="newstitle">Regenradar</h3>
            <p class="newstext" id="regenradar"></p>
            <p class="newstext" id="wetter"></p>
        </div>
        <div class="latestNews">
          <h3 class="newstitle">Heute in der Mensa am <span ondblclick="hockenpong()">Ring</span>:</h3>
          <div class="newstext" id="mensa"></div>
        </div>
      </div>
    </div>
    
    <div id="footer">
      <div id="praesiGeoloek" class="praesidienst">
        <h3 ondblclick='EASTERzwiebelandGEEK()'>Pr&auml;senzdienste GeoL&ouml;k:</h3>
        <?php include("php/praesidienste-geoloek.php"); ?>
      </div>
      <div id="praesiGeofs" class="praesidienst">
        <h3 ondblclick='EASTERbaconandEGGs()'>Pr&auml;senzdienste GI:</h3>
        <?php include("php/praesidienste-geofs.php"); ?>
      </div>
    </div>
  
  </div>  <!-- #content -->
  
  <div id="plakate">
    <hr id="plakatbalken"/>
    <img id="plakat" class="plakate" src="images/geofspacman.png" draggable="false" onmousedown="if (event.preventDefault) event.preventDefault()" />
  </div>
  
</div>  <!-- #container -->
    
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", () => init())
</script>
</body>
</html>
