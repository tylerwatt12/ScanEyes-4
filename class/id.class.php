<?php
class id extends main{
    /**
    *IDs are talkgroups or radio IDs
    */
    private $ID;

    public function getAllIDs(){
        __loadclass('database');
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('ids',"*");
        if ($result){
           foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->ID[] = $resultData;
            }
            return true;
        }else{
            $this->ID[] = array('ID' => $SearchID, 
                                'LABEL' => 'No IDs in DB',
                                'TAG' => '?',
                                'TYPE' => '?',
                                'LOCKOUT' => '0');
            return false;
        }
    }
    public function getDataByID($SearchID){ // searches for ID via key and sets data to $this for other functions
        __loadclass('database');
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('ids',"*",['ID[=]'=>$SearchID]);
        if ($result){
           foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->ID[] = $resultData;
            }
            return true;
        }else{
            $this->ID[] = array('ID' => $SearchID, 
                                'LABEL' => 'Unknown',
                                'TAG' => 'UNKNOWN',
                                'TYPE' => '?',
                                'LOCKOUT' => '0');
            return false;
        }
    }
    public function getDataByTerm($SearchTerm){ // searches for ID via key and sets data to $this for other functions
        __loadclass('database');
        $database = new database($GLOBALS['scaneyes_db_config']);

        $result = $database->select('ids',"*",['OR' => ['LABEL[~]'=>'%'.$SearchTerm.'%','TAG[~]'=>'%'.$SearchTerm.'%'],]);
        if ($result){
            foreach ($result as $resultNumber => $resultData) { // allows multiple queries per object instance
                $this->ID[] = $resultData;
            }
            return true;
        }else{
            $this->ID[] = array('ID' => '0', 
                                'LABEL' => 'Couldn\'t find: '.$SearchTerm,
                                'TAG' => '?',
                                'TYPE' => '?',
                                'LOCKOUT' => '0');
            return false;
        }
    }
    public function filterResultsByField($DATAFIELD,$VALUE){
        foreach ($this->ID as $resultNumber => $resultData) {
           if ($resultData[$DATAFIELD] !== $VALUE) { // if field is not match
               unset($this->ID[$resultNumber]);
           }
        }
    }
    public function filterResultsByFieldReverse($DATAFIELD,$VALUE){
        foreach ($this->ID as $resultNumber => $resultData) {
           if ($resultData[$DATAFIELD] === $VALUE) { // if field is not match
               unset($this->ID[$resultNumber]);
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

        if ($this->ID) {
            if ($METHOD === "ASC") {
                usort($this->ID, array($this,'sortBy'));
            }elseif($METHOD === "DESC"){
                usort($this->ID, array($this,'sortBy'));
                krsort($this->ID);
            }
        }
    }
    /**

    */
    public function limitResults($LIMIT,$SKIP){
        if ($this->CALL) {
            foreach ($this->ID as $resultNumber => $resultData) {
                if ($resultNumber < $SKIP || $resultNumber >= $LIMIT+$SKIP) {
                    unset($this->ID[$resultNumber]);
                }
            }
        }
    }
    public function renumberByID(){ // instead of giving the output array a sequential key number, just use the database key, which is the talkgroup ID or radio ID
        foreach ($this->ID as $resultNumber => $resultData) {
            $tempArray[$resultData["ID"]] = $resultData;
        }
        unset($this->ID); // remove old data
        $this->ID = $tempArray; // swap variables
    }
    public function displayResults(){
        return $this->ID;
    }

}
?>