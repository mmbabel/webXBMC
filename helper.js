xmlHttp = null;

function goTo(section) {
	window.location.href = window.location.href.substr(0, window.location.href.indexOf('?')) + '?view=' + section;
}

function exec(cmd) {
	var Url = "commands.php?cmd=" + cmd;

    xmlHttp = new XMLHttpRequest(); 
    xmlHttp.open( "GET", Url, true );
    xmlHttp.send( null );
}