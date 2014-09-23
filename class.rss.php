<?php
class RSS {	
	
	// Private variables
	private $feedFile;
	private $TTLFile;
	private $TTL;
		
	// Public variables
	public $feedURL;
	private $search;	
	public $feed;
	
	
	// Constructor function
	public function __construct($feedURL, $search=false) {
		$this->feedURL = $feedURL;
		$this->search = $search;
		
		$this->TTLFile = "feedTTL.txt";
		$this->feedFile = "feed.xml";
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Private methods
	/////////////////////////////////////////////////////////////////////////
	
	private function saveFeed() {		
		
		// Saves the RSS Feed to a local file
		file_put_contents($this->feedFile, $this->feed);
	}
	
	private function saveTTL() {
		
		// Saves the RSS Feed TTL
		$feedxml = simplexml_load_file($this->feedFile);
		$this->TTL = time() + ($feedxml->channel->ttl * 60);
		file_put_contents($this->TTLFile, $this->TTL);
	}
		
	private function getTTL() {
		
		// Returns the RSS Feed TTL
		return file_get_contents($this->TTLFile);	
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Public methods
	/////////////////////////////////////////////////////////////////////////
	
	public function getFeed() {
		
		if (is_writeable(ROOT_DIR) && !$this->search) {
			
			// First check for and against the feed TTL (TTL file may not exist)
			$timestamp = strtotime("now");
			$this->TTL = $this->getTTL();
			
			if ($timestamp <= $this->TTL && !empty($this->TTL)) {
				
				// Use the cached feed
				$this->feed = file_get_contents($this->feedFile);
					
			} else {
				
				// Get the feed again, save it and the feed TTL
				$this->feed = file_get_contents($this->feedURL);	
				$this->saveFeed();
				$this->saveTTL();			
			}
					
		} else {
				
			// Working directory is not writeable, and for this example just grab the feed
			$this->feed = file_get_contents($this->feedURL);
		}
	
	return $this->feed;
	
	}
}