<?php
class RSSView {	
	
	// Private variables
	private $viewFile;
	private $itemVars;
	
	// Public variables
	
	// Constructor function
	public function __construct() {
		$this->viewFile = ROOT_DIR . "/views/rss-item.php";
	}
	
	/////////////////////////////////////////////////////////////////////////
	// Private methods
	/////////////////////////////////////////////////////////////////////////
	

	/////////////////////////////////////////////////////////////////////////
	// Public methods
	/////////////////////////////////////////////////////////////////////////
	
	public function setValue($var, $val) {
		$this->itemVars[$var] = $val;
	}

	public function convertTime($rssdate) {
		$itemdate = new DateTime($rssdate);
		
		if ($itemdate->getTimestamp() == 0) {
			// Catch any bad dates in the RSS feed 
			return "";
		} else {
			return date('F jS, Y g:ia', $itemdate->getTimestamp());
		}
	}
	
	public function renderView() {
		extract($this->itemVars);
		
		ob_start();
		
		require $this->viewFile;
		
		echo ob_get_clean();	
	}
}