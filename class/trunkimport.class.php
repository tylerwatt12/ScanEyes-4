<?php
class trunkimport{
    /**
     * imports data from unitrunker.xml into database
     * configure database and file locations in config.php before using this
     * usage:
     * $myvar = new trunkimport();
     * $myvar->purge(); // delete database entries
     * $myvar->syncTG(); // sync talkgroups to database
     * $myvar->syncRD(); // sync radios to database
     *
     * extras:
     * echo $myvar->verbose; // verbose
     */
    protected $sysNum, $xml;
    public $verbose;

    public function __construct(){
        # Load xml into variable
        if (file_exists($GLOBALS['system']['utxmlloc'])) {
            $this->xml = simplexml_load_file($GLOBALS['system']['utxmlloc']);
            $this->verbose .= "{$GLOBALS['system']['utxmlloc']} found and loaded\n";
        } else {
            exit('Failed to open xml. \n
                    <b>Make sure you have unitrunker.xml configured in config.php in the assets folder</b>');
        }
        $this->sysNum = '-1';
        $i = 0;
        foreach ($this->xml->System as $systemInfo) { // search for correct system
            if($systemInfo["id"] == $GLOBALS['system']['sysid']){
                $this->verbose .= "SysID: {$GLOBALS['system']['sysid']} found.\n";
                $this->sysNum = $i;
            }
            $i++;
        }
        if ($this->sysNum === '-1'){
            exit('Failed to find SysID: '.$GLOBALS['system']['sysid']."\n
                    <b>Make sure you have the correct SystemID configured in config.php in the assets folder</b>");
        }
    }
    public function purge(){
        __loadclass('database');
        $this->verbose .= "Truncating table <b>ids</b>\n";

        $dbh = new database($GLOBALS['scaneyes_db_config']);

        $dbh->pdo->beginTransaction();

        $dbh->query("TRUNCATE TABLE ids;");// dump everything

        $dbh->pdo->commit();
    }
    public function syncAll(){ // syncs everything including empty radioIDs and Talkgroups

        __loadclass('database');
        $dbh = new database($GLOBALS['scaneyes_db_config']);
        $dbh->pdo->beginTransaction();

        foreach ($this->xml->System[(integer)$this->sysNum]->User as $systemNumber => $userInfo) { // build radio id list
            $this->verbose .= "Inserting {$userInfo["id"]} {$userInfo["label"]} - {$userInfo["lockout"]}\n";
            $dbh->insert("ids", [
                "ID" => substr((string)$userInfo["id"],0,8),
                "LABEL" => (string)$userInfo["label"],
                "TYPE" => (string)"RD",
                "LOCKOUT" => (string)$userInfo["lockout"]
            ]);
        }
        foreach ($this->xml->System[(integer)$this->sysNum]->Group as $systemNumber => $groupInfo) { // build talkgroup list
            $this->verbose .= "Inserting <{$groupInfo["id"]} {$groupInfo["label"]} - {$groupInfo["lockout"]}\n";
            $dbh->insert("ids", [
                "ID" => substr((string)$groupInfo["id"],0,8),
                "TAG" => (string)$groupInfo["tag"],
                "LABEL" => (string)$groupInfo["label"],
                "TYPE" => (string)"TG",
                "LOCKOUT" => (string)$groupInfo["lockout"]
            ]);
        }
        $dbh->pdo->commit();
    }
    public function sync(){

        __loadclass('database');
        $dbh = new database($GLOBALS['scaneyes_db_config']);
        $dbh->pdo->beginTransaction();

        foreach ($this->xml->System[(integer)$this->sysNum]->User as $systemNumber => $userInfo) { // build radio id list
            if (empty($userInfo["label"])) {
                $this->verbose .= "NOT Inserting {$userInfo["id"]} {$userInfo["label"]} - {$userInfo["lockout"]}\n";
            }else{
                $this->verbose .= "Inserting {$userInfo["id"]} {$userInfo["label"]} - {$userInfo["lockout"]}\n";
                $dbh->insert("ids", [
                    "ID" => substr((string)$userInfo["id"],0,8),
                    "LABEL" => (string)$userInfo["label"],
                    "TYPE" => (string)"RD",
                    "LOCKOUT" => (string)$userInfo["lockout"]
                ]);
            }
        }
        foreach ($this->xml->System[(integer)$this->sysNum]->Group as $systemNumber => $groupInfo) { // build talkgroup list
            if (empty($groupInfo["label"]) && empty($groupInfo["tag"])){
                $this->verbose .= "NOT Inserting {$groupInfo["id"]} {$groupInfo["label"]} - {$groupInfo["lockout"]}\n";
            }else{
                $this->verbose .= "Inserting {$groupInfo["id"]} {$groupInfo["label"]} - {$groupInfo["lockout"]}\n";
                $dbh->insert("ids", [
                    "ID" => substr((string)$groupInfo["id"],0,8),
                    "TAG" => (string)$groupInfo["tag"],
                    "LABEL" => (string)$groupInfo["label"],
                    "TYPE" => (string)"TG",
                    "LOCKOUT" => (string)$groupInfo["lockout"]
                ]);
            }
        }
        $dbh->pdo->commit();

    }

}
?>