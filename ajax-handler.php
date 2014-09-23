<?php
// Setting the default timezone for this example to match the RSS feed timezone
date_default_timezone_set('America/New_York');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

// First make sure the HTTP GET variable is set
if (!isset($_POST['action'])) {
    header('400 Bad Request');
    exit;
}    

// To keep things simple in this example, the ajax handler is essentially serving as the controller without 
// being abstracted out to its own class. In a more robust application this would obviously not be ideal.

try {
	// Definitions
	define('ROOT_DIR', realpath(dirname(__FILE__)));
	
	// Requirements and variable declarations
	require("class.rss.php");
	require("class.rss-view.php");
	
	$itemCount = 5;
	$html = "";
	$query = "";
	$doSearch = false;
	
	// Use the action as provided by the AJAX request to determine what URL the RSS model will get data from
	if ($_POST["action"] == "getLatestNews") {
		$feedURL = "http://news.yahoo.com/rss/";
	} elseif ($_POST["action"] == "search") {
		$query = urlencode($_POST["query"]);
		$doSearch = true;
		$feedURL = "http://news.search.yahoo.com/news/rss?p=" . $query . "&c=&eo=UTF-8";
	} else {
    	header('400 Bad Request');
    	exit;		
	};
	
	// Create a handler variable and instantiate the RSS class	
	// $doSearch is set to false by default because those results should not be cached 
	$rssh = new RSS($feedURL, $doSearch);
	
	// Call the getFeed method which will return the RSS XML based on the URL.
	$feed = $rssh->getFeed();
	
	// Take the resulting string and interpret as a XML object
	$feedxml = simplexml_load_string($feed);
	
	// Increment to $itemCount and load each XML item into a view, and add to $html string
	if (count($feedxml->channel->item) == 0) {
		$html .= "<div class=\"alert alert-warning\" role=\"alert\">Sorry, your search for <strong>" . $_POST["query"] . "</strong> returned no results.</div>";
	} else {
		for ($i=0; $i<$itemCount; $i++) {
			
			$view = new RSSView();
			
			$view->setValue("title", $feedxml->channel->item[$i]->title);
			$view->setValue("description", $feedxml->channel->item[$i]->description);
			$view->setValue("link", $feedxml->channel->item[$i]->link);
			
			$pubDate = $view->convertTime($feedxml->channel->item[$i]->pubDate);
			$view->setValue("pubDate", $pubDate);
			
			$html .= $view->renderView();
		}
	}
	// Echo HTML back to the browser
	echo $html;
		
} catch(Exception $e) {
    header('500 Internal Server Error');
    exit;
}

?>