<?php
class call extends main{
    /**
    *A calls is a conversation between a talkgroup and radio, or radio and radio (iCall)
    */
    private $CALL;

    public function getCallByID($CALLID){ // searches for call ID and adds it to the object array
        __loadclass('database');
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('calls',"*",['TIME[=]'=>$CALLID]);
        if ($result){
           foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->CALL[] = $resultData;
            }
            return true;
        }else{
            return false;
        }
    }

    public function getCallBySrcTgt($FIELD,$SEARCH){
        __loadclass('database');
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('calls',"*",[$FIELD.'[=]' => $SEARCH]);
        if ($result){
            foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->CALL[] = $resultData;
            }
            return true;
        }else{
            $this->CALL[] = array('TIME' => '0', 
                                'SRCID' => '0',
                                'TGTID' => '0');
            return false;
        }
    }

    public function getCallByTimePeriod($BEGIN,$END){
        __loadclass('database');

        if (strlen($BEGIN) !== 16 && strlen($END) !== 16 && is_numeric($BEGIN) === FALSE && is_numeric($END) === FALSE){ // if query isn't a Call ID
            // turn it into a call ID
            $BEGIN = $this->convertTime($BEGIN,'TIME');
            $END = $this->convertTime($END,'TIME');
        }elseif(strlen($BEGIN) == 10 && strlen($END) == 10 && is_numeric($BEGIN) === TRUE && is_numeric($END) === TRUE){ // if query is a unixts without 16 digits
            $BEGIN = $BEGIN."000000";
            $END = $END."999999";
        }
        
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('calls',"*",["TIME[<>]" => [$BEGIN,$END]]);
        if ($result){
           foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->CALL[] = $resultData;
            }
            return true;
        }else{
            return false;
        }
    }
    public function filterResultsByField($DATAFIELD,$VALUE){
        foreach ($this->CALL as $resultNumber => $resultData) {
           if ($resultData[$DATAFIELD] !== $VALUE) { // if field is not match
               unset($this->CALL[$resultNumber]);
           }
        }
    }
    public function filterResultsByFieldReverse($DATAFIELD,$VALUE){
        foreach ($this->CALL as $resultNumber => $resultData) {
           if ($resultData[$DATAFIELD] === $VALUE) { // if field IS a match
               unset($this->CALL[$resultNumber]);
           }
        }
    }
    /**
        Sorting functions below
    */
    public function setSortField($FIELD){
        $this->sortField = $FIELD;
    }

    protected function sortBy($a, $b) { // function only used to compare fields for sorting in function below
            return strcasecmp($a[$this->sortField], $b[$this->sortField]);
    }

    public function sortResultsByField($FIELD,$METHOD){
        $this->setSortField($FIELD);

        if ($this->CALL) {
            if ($METHOD === "ASC") {
                usort($this->CALL, array($this,'sortBy'));
            }elseif($METHOD === "DESC"){
                usort($this->CALL, array($this,'sortBy'));
                krsort($this->CALL);
            }
        }
    }
    /**

    */
    public function limitResults($LIMIT,$SKIP){ // drumps array of data
        if ($this->CALL) {
            foreach ($this->CALL as $resultNumber => $resultData) {
                if ($resultNumber < $SKIP || $resultNumber >= $LIMIT+$SKIP) {
                    unset($this->CALL[$resultNumber]);
                }
            }
        }
    }

    public function fileLength($CALLID,$SRC = FALSE,$TGT = FALSE){
        __loadclass('mp3file');
        if ($SRC === FALSE || $TGT === FALSE) { // if no source or target provided, use lookup
            $this->getCallByID($CALLID);
            $callInfo = $this->displayResults();
           var_dump($callInfo);
            if (is_null($callInfo)) { // if call can't be found in DB
                return 0; // return 0 length call seconds
            }

            $SRC = $callInfo[0]["SRCID"];
            $TGT = $callInfo[0]["TGTID"];
        }
        $folderDate = main::convertTime($CALLID,'Y-m-d'); // convert unix timestamp to folder name of day
        // convert callID to directory
        $mp3file = new MP3File($GLOBALS['system']['localfilelocation'].$TGT."\\".$SRC."\\".$folderDate."\\".$CALLID.$GLOBALS['system']['fileext']); // find full file path
        return (string)$mp3file->getDurationEstimate(); // return length of file using mp3lib
    }
    public function displayResults($join = FALSE){
        if ($join === TRUE) { // Join info from IDs table and get call length
            $allIDSHandle = new id;
            $allIDSHandle->getAllIDs(); // get all source and target IDs
            $allIDSHandle->renumberByID(); // renumber array by ID number

            $queryResult = $allIDSHandle->displayResults();
            foreach ($this->CALL as $resultNumber => $resultData) {
                $this->CALL[$resultNumber]["LENGTH"] = $this->fileLength($resultData["TIME"],$resultData["SRCID"],$resultData["TGTID"]); // get file length
                if (@$queryResult[$resultData["TGTID"]]) { // get target info
                    $this->CALL[$resultNumber]["TGTLABEL"] = $queryResult[$resultData["TGTID"]]["LABEL"];
                    $this->CALL[$resultNumber]["TGTTAG"] = $queryResult[$resultData["TGTID"]]["TAG"];
                }
                if (@$queryResult[$resultData["SRCID"]]) { // get source info
                    $this->CALL[$resultNumber]["SRCLABEL"] = $queryResult[$resultData["SRCID"]]["LABEL"];
                    $this->CALL[$resultNumber]["SRCTAG"] = $queryResult[$resultData["SRCID"]]["LABEL"];
                }
                
                
            }
            return($this->CALL); // return with joined info
        }
        return $this->CALL;
    }

}
?>