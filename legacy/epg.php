<?php

class epg {
	private $host;
	private $port;
	
	function getEpgDB()
	{
		try {
		$ch = curl_init("http://".$this->host.":".$this->port."/vfs/special://database/Epg6.db");
		$fp = fopen("tmpdata/epg.db", "w");
		curl_setopt($ch, CURLOPT_FILE, $fp);
	
		curl_exec($ch);
		curl_close($ch);
		fclose($fp); }
		catch (Exception $e) {
			var_dump($e);
		}
	}
	
	function epg($hostg, $portg) {
		$this->host = $hostg;
		$this->port = $portg;
		$dbfile = "tmpdata/epg.db";
		if(file_exists($dbfile)) {
			if(filemtime($dbfile) < mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))) {
				$this->getEpgDB();
			}
		} else {
			$this->getEpgDB();
		}
	}
	
	function getEpgNow($channel)
	{
		$db = new SQLite3("tmpdata/epg.db");
		$results = $db->query('SELECT * FROM epgtags LEFT JOIN epg ON(epg.idEpg = epgtags.idEpg) WHERE iStarttime < '.time().' AND iEndtime > '.time()." AND epg.sName LIKE '".$channel."'");
		while ($row = $results->fetchArray()) {
		    return "Now: ".$row['sTitle']." Start: ".date("H:i",$row['iStartTime'])." End: ".date("H:i",$row['iEndTime'])."<br />";
		}
	}
	
	function getEpgNext($channel)
	{
		$db = new SQLite3("tmpdata/epg.db");
		$results = $db->query('SELECT * FROM epgtags LEFT JOIN epg ON(epg.idEpg = epgtags.idEpg) WHERE iStarttime > '.time()." AND epg.sName LIKE '".$channel."' ORDER BY iStartTime ASC");
		while ($row = $results->fetchArray()) {
		    return "Next: ".$row['sTitle']." Start: ".date("H:i",$row['iStartTime'])." End: ".date("H:i",$row['iEndTime'])."<br />";
		}
	}
}

?>
