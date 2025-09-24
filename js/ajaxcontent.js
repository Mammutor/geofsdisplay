function loadMensa() {
  $.ajax({
    url: 'php/mensaplaene.php',
    type: 'GET',
    success: function(responseText) { $('#mensa').html(responseText); },
    error: function(responseText) { console.error("could not get mensaplaene.php"); }
  });
  // not really a need to refresh (the display starts freshly every morning and the day's mensaplan is basically never changed during that day)
}

function refreshFahrplan() {
  $.ajax({
    url: 'php/fahrplan.php',
    type: 'GET',
    success: function(responseText) { $('#fahrplan').html(responseText); },
    error: function(responseText) { console.error("could not get fahrplan.php"); }
  });
  // refresh every 15 seconds
  window.setTimeout(refreshFahrplan, 15*1000);
}

function refreshWetter() {
  $.ajax({
    url: 'php/wetter.php',
    type: 'GET',
    success: function(responseText) { $('#wetter').html(responseText); },
    error: function(responseText) { console.error("could not get wetter.php"); }
  });
  // refresh every 5 minutes
  window.setTimeout(refreshWetter, 5*60*1000);
}

function refreshRegenradar() {
  document.getElementById("regenradar").innerHTML =
      `<iframe sandbox="allow-scripts allow-same-origin" src="https://widgets.meteox.com/de-DE/widgets/radar/location/20693/rain?z=11" style="width:100%!important;max-width:500px!important;max-height:500px!important;height:100%!important;border:none!important;box-sizing:border-box!important;" scrolling="no" frameborder="0"></iframe>`;
  window.setTimeout(refreshRegenradar, 5 * 60 * 1000);
}
