<?php
class query{

	public $allIDS;
	public $result;

	public function __construct(){
		// Get and cache all talkgroups
		$allIDSHandle = new id;
		$allIDSHandle->getAllIDs();
		$allIDSHandle->renumberByID();
		$queryResult = $allIDSHandle->displayResults();
		$this->allIDS = $queryResult;
	}
	public function getExtnededInfo($xyz){ // FOR TESTING ONLY
		$callHandle = new call;
		$callHandle->getCallByTimePeriod($DATE,$DATE." +1 day"); // get all calls from today
		$todaysCalls = $callHandle->displayResults();
		foreach ($todaysCalls as $resultNumber => $resultData) {
			$outputArray[$resultNumber] = $resultData; // put current info into new array

			$outputArray[$resultNumber]["TGLBL"] = $resultData; // put current info into new array
			$outputArray[$resultNumber]["TGTAG"] = $resultData; // put current info into new array
			$outputArray[$resultNumber]["SRCTAG"] = $resultData; // put current info into new array
			$outputArray[$resultNumber]["SRCLABEL"] = $resultData; // put current info into new array
			
			$outputArray[$resultNumber]["TIME"] = $resultData; // put current info into new array
			$outputArray[$resultNumber]["DATE"] = $resultData; // put current info into new array

			$outputArray[$resultNumber]["LENGTH"] = $resultData; // put current info into new array
		}
	}

	public function getDateIDS($DATE){ // used on main page to show most active talkgroups for the day
		$callHandle = new call;
		$callHandle->getCallByTimePeriod($DATE,$DATE." +1 day"); // get all calls from today
		$todaysCalls = $callHandle->displayResults();
		$outputArray = array();
		foreach ($todaysCalls as $resultNumber => $resultData) { // compound each call into an array with tgtid and call amount
			if (@!$outputArray[$resultData["TGTID"]]) {
				$outputArray[$resultData["TGTID"]]["COUNT"] = 1;
				@$outputArray[$resultData["TGTID"]]["TAG"] = $this->allIDS[$resultData["TGTID"]]["TAG"];
				@$outputArray[$resultData["TGTID"]]["LABEL"] = $this->allIDS[$resultData["TGTID"]]["LABEL"];
				$outputArray[$resultData["TGTID"]]["LASTCALLID"] = $resultData["TIME"];
				$outputArray[$resultData["TGTID"]]["LASTCALLAGO"] = main::getTimeago(substr($resultData["TIME"],0,10));
			}
			$outputArray[$resultData["TGTID"]]["COUNT"]++; // number of calls
			@$outputArray[$resultData["TGTID"]]["TAG"] = $this->allIDS[$resultData["TGTID"]]["TAG"];
			@$outputArray[$resultData["TGTID"]]["LABEL"] = $this->allIDS[$resultData["TGTID"]]["LABEL"];
			$outputArray[$resultData["TGTID"]]["LASTCALLID"] = $resultData["TIME"]; // call ID
			$outputArray[$resultData["TGTID"]]["LASTCALLAGO"] = main::getTimeago(substr($resultData["TIME"],0,10)); // about x units ago

		}
		arsort($outputArray); // sort by amount of calls
		return $outputArray;
	}
	public function getCallsByTGTID($TGTID,$SKIP,$LIMIT,$SORTBY,$METHOD){
		$callHandle = new call;
		
	}


}

?>