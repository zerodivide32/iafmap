<!DOCTYPE html >
<?php
include_once 'deebee.php';
?>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

    <link href="nouislider.min.css" rel="stylesheet">
    <link href="map.css" rel="stylesheet">


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
</head>
<body>
    <div style="display:flex;flex-flow:row ;justify-content:flex-start;height:95px;">
        <div>
            <a href="http://iceagefarmer.com">
                <img src="http://iceagefarmer.com/iafp160.png" style="object-fit: scale-down;height:90px;">
            </a>
        </div>

        <div style="font-family: Garamond; font-size: 26px;padding-left:4px;">
            Ice Age Farmer:<br/>
            Grand Solar Minimum<br/>
            Crop Loss Map
        </div>

        <span style="padding-left: 60px; padding-right: 4px;padding-top: 4px;padding-bottom: 4px;font-family: Garamond;">
            Modern agriculture is at risk due to cyclical changes in our sun&apos;s output that drive climate change on our planet.<br/><br/>
            This page tracks crop losses to the <a href="http://wiki.iceagefarmer.com/wiki/Grand_Solar_Minimum_Symptoms">Grand Solar Minimum</a>: hail, storms, flooding, drought, early/late frosts...
            <br/><br/>
            Made possible by my <a href="http://patreon.com/IceAgeFarmer">Patreon supporters</a> -- consider supporting my work!  Thanks.
            &nbsp; &nbsp; &nbsp; 
            <a href="mailto:iceagefarmer@gmail.com">Contact / Submit News</a>
        </span>


        <!-- BEGIN map controls -->
        <div style="display: flex;flex-flow: row;justify-content: flex-end;height: 100px;" >

            <div id="yearRangeHolder" class="rangeval">
                <div id="yearRange" style="width:150px;"></div><br>
                <div id="yearRangeValue" class="span">1950 ~ 2020</div>
            </div>
            <!--<div id="pestHolder" class="btn" style="justify-content:center;">
                <img src="img/turq19x35.png"><br>
                <div id="swPest" class="toggle" ></div>
            </div> -->
            <?php
            //$result = mysqli_query($connection, "SELECT * FROM marker_types;");
            $result = pg_query($connection, "SELECT * FROM marker_types;");
            if (!$result) {
                die('Invalid query: ' . pg_last_error());
            }
            while ($row = @pg_fetch_assoc($result)) {
                $img = $row['typeimage'];
                $typeName = $row['typename'];
                $checkboxName = "ck" . $typeName;
                ?>

                <div class="btn" onClick='toggleButton(document.getElementById("<?php echo $checkboxName; ?>"))'>
                    <input type="checkbox" id="<?= $checkboxName ?>" value="<?= $typeName; ?>" checked><img class="img" src="<?= $img ?>" width="22" height="38"> 
                    <div id="<?= "lbl" . $typeName ?>" class="center" ><?= $typeName ?></div>
                </div>
            <?php }
            pg_close();
            ?>

        </div></div>
    <div id="map" ></div>
    <script src="nouislider.min.js"></script>
    <script src="map.js"></script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC__wCPpbbfgB2iGk1loB4cbanMLSNwtiY&callback=initMap">
    </script>
</body>
</html>
