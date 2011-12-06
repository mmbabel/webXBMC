<?php
include("requests.php");
if($_GET["cmd"] == "play") {
	play(getClient(), $_GET["item"], $_GET["kind"]);
} else if($_GET["cmd"] == "play_pause") {
	play_pause(getClient());
} else if($_GET["cmd"] == "stop") {
	stop(getClient());
} else if($_GET["cmd"] == "vol_down") {
	vol_down(getClient());
} else if($_GET["cmd"] == "vol_up") {
	vol_up(getClient());
} else if($_GET["cmd"] == "mute") {
	mute(getClient());
} else if($_GET["cmd"] == "back") {
	back(getClient());
} else if($_GET["cmd"] == "home") {
	home(getClient());
} else if($_GET["cmd"] == "left") {
	left(getClient());
} else if($_GET["cmd"] == "right") {
	right(getClient());
} else if($_GET["cmd"] == "up") {
	up(getClient());
} else if($_GET["cmd"] == "down") {
	down(getClient());
} else if($_GET["cmd"] == "select") {
	select(getClient());
}
?>
