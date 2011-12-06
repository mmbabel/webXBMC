<?php
include("requests.php");
?>
<html>
<head>
<title>webXBMC</title>
<link media="only screen and (min-device-width: 640px)" rel="stylesheet" type="text/css" href="style/desktop.css">
<link rel="stylesheet" type="text/css" href="style/style.css">
<script type="text/javascript" src="helper.js"></script>
<link rel=ÓstylesheetÓ type=Ótext/cssÓ href=Óhandheld.cssÓ media=ÓhandheldÓ/>
<link media="only screen and (max-device-width: 480px)" href="style/handheld.css" type= "text/css" rel="stylesheet">
<meta name="viewport" content="width=480" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
</head>
<body>
<center>
<div id="sremote">
<img src="img/button_play_pause.png" alt="play/pause" onclick="exec('play_pause')" />
<img src="img/button_stop.png" alt="stop" onclick="exec('stop')" />
<img src="img/button_vol_up.png" alt="vol up" onclick="exec('vol_up')" />
<img src="img/button_vol_down.png" alt="vol down" onclick="exec('vol_down')" />
<img src="img/button_vol_mute.png" alt="mute" onclick="exec('mute')" />
<img src="img/button_up.png" alt="home" onclick="goTo('main')" />
</div><div id="seperator"></div>
<div id="placeholder"></div>
</center>
<?php
if($_GET["view"] == "movies")
{
	getMovies(getClient());
} else if($_GET["view"] == "tv") {
	getFiles(getClient(), 'pvr://channels/tv/all/');
} else if($_GET["view"] == "recordings") {
	getFiles(getClient(), 'pvr://recordings/client_0001/');
} else if($_GET["view"] == "tv_shows") {
	getTvShows(getClient());
} else if($_GET["view"] == "seasons") {
	getSeasons(getClient(), $_GET["tv_show"]);
} else if($_GET["view"] == "episodes") {
	getEpisodes(getClient(), $_GET["tv_show"], $_GET["season"]);
} else if($_GET["view"] == "remote") {
	include("remote.php");
} else {
?>
<center>
<div id="tv" class="topmenu" onclick="goTo('tv')"><font>TV</font></div>
<div id="recordings" class="topmenu" onclick="goTo('recordings')"><font>Recordings</font></div>
<div id="tv_shows" class="topmenu" onclick="goTo('tv_shows')"><font>TV Shows</font></div>
<div id="movies" class="topmenu" onclick="goTo('movies')"><font>Movies</font></div>
<div id="remote" class="topmenu" onclick="goTo('remote')"><font>Remote</font></div>
</center>
<?php }  
include("legacy/epg.php");
include("config.php"); 
new epg($host, $port); ?>
</body>
</html>
