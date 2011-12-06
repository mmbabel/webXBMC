<?php

function printMovie($title, $thumbnail, $plot, $onclick, $watched)
{
	include("config.php");
	printf("<div class=\"item\" onclick=\"%s\"><div class=\"prevIMG\">", $onclick);
	if($watched) {
		printf("<img src=\"img/watched.png\" class=\"watched\" alt=\"watched\" />");
	}
	printf("<img src=\"http://%s:%s/vfs/%s\" alt=\"%s\" />", $host, $port, $thumbnail,$title);
	printf("</div><div class=\"dsc\">");
	printf("<span>%s</span><br /><font>%s</font>", $title, $plot);
	printf("</div></div>");
}

function printBanner($title, $thumbnail, $plot, $onclick)
{
	include("config.php");
	printf("<div class=\"banner_item\" onclick=\"%s\">", $onclick);
	printf("<img src=\"http://%s:%s/vfs/%s\" alt=\"%s\" />", $host, $port, $thumbnail,$title);
	printf("</div>");
}

function getClient()
{
	include("config.php");
	$params = $user.":".$pass."@".$host.":".$port;
	require_once 'xbmc-php-rpc/rpc/HTTPClient.php';
	try {
	    $client = new XBMC_RPC_HTTPClient($params);
	} catch (XBMC_RPC_ConnectionException $e) {
	    die($e->getMessage());
	}
	return $client;
}

function getMovies($client) {
	$req['properties'] = array('thumbnail','plot','playcount');
	try {
	    $response = $client->VideoLibrary->GetMovies($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
	
	foreach($response['movies'] as $movie)
	{
		if($movie['thumbnail'] != "") {
			$thumbnail = $movie['thumbnail'];
		} else {
			$thumbnail = 'special://skin/media/DefaultVideo.png';
		}
		if($movie['playcount'] > 0) {
			$watched = true;
		} else {
			$watched = false;
		}
		printMovie($movie['label'], $thumbnail, $movie['plot'], "exec('play&item=".$movie['movieid']."&kind=movieid')", $watched);
	}
}

function getFiles($client, $path) {
	include("legacy/epg.php");
	include("config.php");
	$req['properties'] = array('thumbnail','plot','playcount');
	$req['directory'] = $path;
	try {
	    $response = $client->Files->GetDirectory($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}

	foreach($response['files'] as $movie)
	{
		if($movie['thumbnail'] != "") {
			$thumbnail = $movie['thumbnail'];
		} else {
			$thumbnail = 'special://skin/media/DefaultVideo.png';
		}
		if($movie['playcount'] > 0) {
			$watched = true;
		} else {
			$watched = false;
		}
		if($path == "pvr://channels/tv/all/") {
			$epg = new epg($host, $port);
			$epgdata = $epg->getEpgNow($movie['label'])."<br />".$epg->getEpgNext($movie['label']);
		}
		printMovie($movie['label'], $thumbnail, $epgdata, "exec('play&item=".$movie['file']."&kind=file')", $watched);
	}
}

function getTvShows($client) {
	$req['properties'] = array('thumbnail','plot','playcount');
	try {
	    $response = $client->VideoLibrary->GetTVShows($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
	foreach($response['tvshows'] as $movie)
	{
		if($movie['thumbnail'] != "") {
			$thumbnail = $movie['thumbnail'];
		} else {
			$thumbnail = 'special://skin/media/DefaultVideo.png';
		}
		if($movie['playcount'] > 0) {
			$watched = true;
		} else {
			$watched = false;
		}
		printBanner($movie['label'], $thumbnail, "", "goTo('seasons&tv_show=".$movie['tvshowid']."')", $watched);
	}
}

function getSeasons($client, $tvshowid) {
	$req['properties'] = array('thumbnail','playcount','season');
	$req['tvshowid'] = (int)$tvshowid;
	try {
	    $response = $client->VideoLibrary->GetSeasons($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
	foreach($response['seasons'] as $movie)
	{
		if($movie['thumbnail'] != "") {
			$thumbnail = $movie['thumbnail'];
		} else {
			$thumbnail = 'special://skin/media/DefaultVideo.png';
		}
		if($movie['playcount'] > 0) {
			$watched = true;
		} else {
			$watched = false;
		}
		printMovie($movie['label'], $thumbnail, "", "goTo('episodes&tv_show=".$tvshowid."&season=".$movie['season']."')", $watched);
	}
}

function getEpisodes($client, $tvshowid, $season) {
	$req['properties'] = array('thumbnail','playcount','season');
	$req['tvshowid'] = (int)$tvshowid;
	$req['season'] = (int)$season;
	try {
	    $response = $client->VideoLibrary->GetEpisodes($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
	foreach($response['episodes'] as $movie)
	{
		if($movie['thumbnail'] != "") {
			$thumbnail = $movie['thumbnail'];
		} else {
			$thumbnail = 'special://skin/media/DefaultVideo.png';
		}
		if($movie['playcount'] > 0) {
			$watched = true;
		} else {
			$watched = false;
		}
		printMovie($movie['label'], $thumbnail, "", "exec('play&item=".$movie['episodeid']."&kind=episodeid')", $watched);
	}
}

function getCurrVolume($client) {
	$req['properties'] = array('volume');
	try {
	    $response = $client->Application->GetProperties($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
	
	return $response['volume'];
}

function Play($client, $item, $kind) {
	if($kind == "file") {
		$req['item'][$kind] = $item;
	} else {
		$req['item'][$kind] = (int)$item;
	}

	try {
	    $response = $client->Player->Open($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function Play_Pause($client) {
	$req['playerid'] = 1;
	try {
	    $response = $client->Player->PlayPause($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function Stop($client) {
	$req['playerid'] = 1;
	try {
	    $response = $client->Player->Stop($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function vol_up($client) {
	$req['volume'] = getCurrVolume($client) + 5;
	try {
	    $response = $client->Application->SetVolume($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function vol_down($client) {
	$req['volume'] = getCurrVolume($client) - 5;
	try {
	    $response = $client->Application->SetVolume($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function mute($client) {
	$req = array('toggle');
	try {
	    $response = $client->Application->SetMute($req);
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function up($client) {
	try {
	    $response = $client->Input->Up();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function left($client) {
	try {
	    $response = $client->Input->Left();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function right($client) {
	try {
	    $response = $client->Input->Right();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function down($client) {
	try {
	    $response = $client->Input->Down();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function select($client) {
	try {
	    $response = $client->Input->Select();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function back($client) {
	try {
	    $response = $client->Input->Back();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}

function home($client) {
	try {
	    $response = $client->Input->Home();
	} catch (XBMC_RPC_Exception $e) {
	    die($e->getMessage());
	}
}
?>
