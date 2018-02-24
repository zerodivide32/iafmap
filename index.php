<?php

require_once( 'cfg.inc' );
$events = getMarkers();

?>

<html>
<head>
<title> ice age farmer :: grand solar minimum crop loss map </title>
<meta property="fb:page_id" content="455859958082919">
    <meta charset="UTF-8" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="ice age farmer :: grand solar minimum crop loss map" />
    <!-- 160x160 is too small:     <meta property="og:image" content="http://iceagefarmer.com/iafp160.png" />-->
    <meta property="og:image" content="http://iceagefarmer.com/iafPodcastLogo-fb.jpg" />
<!--    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" /> -->
    <meta property="og:description" content="As the Grand Solar Minimum deepens, Ice Age Farmer's real-time map of crop losses visualizes the mounting losses." />
    <!--meta property="og:url" content="http://www.iceagefarmer.com/map/" /-->
    <meta property="og:site_name" content="ice age farmer" />
    <meta property="og:locale" content="en_US" />
<style>
#map {
height: 85%;
width: 100%;
}
</style>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmApiKey ?>&v=3.exp&callback=initMap"></script>
    <script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
                center: {lat: 0, lng: 0}
                });
<?php

foreach ($events as $e) {
    $id = $e[ 'id' ];

    $title = str_replace("\"","",$e[ 'title' ]);
    echo "var w$id = new google.maps.InfoWindow({\n";
    $cnt = $title . '<br/><i><small><a href="' . $e[ 'url' ] . '">source</a></i>';
    $cnt = str_replace("\"", "\\\"", $cnt);
    echo '  content: "' . $cnt . "\"\n";
    echo "});\n";
    echo "var m$id = new google.maps.Marker({\n";
    echo '  position: {lat: ' . $e[ 'lat' ] . ', lng: ' . $e[ 'lng' ] . "},\n";
    echo '  title: "' . $title . '"' .",\n";
    switch( $e[ 'type' ] ) {
    case 'hail':
        //echo '  icon: "http://iceagefarmer.com/map/img/hail.png",';
        echo '  icon: {size: new google.maps.Size(20, 32), origin: new google.maps.Point(0, 0), anchor: new google.maps.Point(0, 32), ';
        echo '  url: "http://iceagefarmer.com/map/img/hail.png" },';
        break;
    default:
    }
    echo "  map: map\n";
    echo "});\n";
    echo "google.maps.event.addListener(m$id, 'click', (function () {";
    echo "w$id.open(map, m$id);\n";
    echo "}));\n";
}
?>    
    }
          
    </script>
</head>
<body>
<table border=0 cellpadding=0 cellspacing=0 height="10%" width="100%">
 <tr><td height="10%"><a href="http://iceagefarmer.com"><img src="http://iceagefarmer.com/iafp160.png" height="70%" border=0></a></td>
     <td align="right" style="padding-left: 30px">
<span style="font-family: Garamond; font-size: 32px;">
      Ice Age Farmer:<br/>
      Grand Solar Minimum<br/>
      Crop Loss Map
      </span>
      </td>
      <td style="padding-left: 120px; font-family: Garamond;">
      Modern agriculture is at risk due to cyclical changes in our sun&apos;s output that drive climate change on our planet.<br/><br/>
   This page tracks crop losses to the <a href="http://wiki.iceagefarmer.com/wiki/Grand_Solar_Minimum_Symptoms">Grand Solar Minimum</a>: hail, storms, flooding, drought, early/late frosts...
 <br/><br/>
Made possible by my <a href="http://patreon.com/IceAgeFarmer">Patreon supporters</a> -- consider supporting my work!  Thanks.
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
   <a href="mailto:iceagefarmer@gmail.com">Contact / Submit News</a>
      </td>
</tr></table>
<div id="map"></div>

<?php

//////////////////////
// Footer.
echo "Showing " . count($events) . " events.";
?>                                              
</body>
</html>
