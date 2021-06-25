<?php 
require __DIR__ . "/../connexion/connexiondb.php";

class Dbapi{


    //**
    //  * insert data into table data_twd
    //  *
    //  * @param string $ile
    //  * @param sting $code_societe
    //  * @param mixed $percent
    //  * @param mixed $date
    //  * @return void
    //  */
    public function insertdata($ile,$code_societe,$percent,$date){
        global $conn;

        $req = 'insert into `data_twd` (`ile`,`code_societe`,`date`,`y`) value ("'.$ile.'","'.$code_societe.'","'.$date.'",'.$percent.')';
        $res = $conn->query($req);

    }


    public function insertdatatravel($ile,$code_societe,$percent,$date){
        global $conn;

        $req = 'insert into `data_twd_travel` (`ile`,`code_societe`,`date`,`y`) value ("'.$ile.'","'.$code_societe.'","'.$date.'",'.$percent.')';
        $res = $conn->query($req);

    }
    
    public function truncate(){
        global $conn;
        $req = 'DELETE FROM `data_twd`';
        $res = $conn->query($req);

        $this->reset();
    }
   
    public function truncate_travel(){
        global $conn;
        $req = 'DELETE FROM `data_twd_travel`';
        $res = $conn->query($req);

        $this->reset_travel();
    }

    public function reset(){
        global $conn;

        $req = 'ALTER TABLE `data_twd` AUTO_INCREMENT = 1';
        $res = $conn->query($req);
    }

    public function reset_travel(){
        global $conn;

        $req = 'ALTER TABLE `data_twd_travel` AUTO_INCREMENT = 1';
        $res = $conn->query($req);
    }

    public function getdata(){
        global $conn;
        $req = 'SELECT code_societe ,ile ,date ,count(date) as count_date ,sum(y) as y FROM `data_twd` GROUP BY ile, date, code_societe ORDER BY date, code_societe,ile DESC'; 
        $res = $conn->query($req); 

        return $res;
    }

    public function getdata_ct(){
        global $conn;
        $req = 'SELECT * FROM `data_twd_travel` WHERE code_societe = "CATAM" AND ile = "T"'; 
        $res = $conn->query($req); 

        return $res;
    }

    public function getdata_ft(){
        global $conn;
        $req = 'SELECT * FROM `data_twd_travel` WHERE code_societe = "FERRY" AND ile = "T"'; 
        $res = $conn->query($req); 

        return $res;
    }

    public function getdata_cm(){
        global $conn;
        $req = 'SELECT * FROM `data_twd_travel` WHERE code_societe = "CATAM" AND ile = "M"'; 
        $res = $conn->query($req); 

        return $res;
    }

    public function getdata_fm(){
        global $conn;
        $req = 'SELECT * FROM `data_twd_travel` WHERE code_societe = "FERRY" AND ile = "M"'; 
        $res = $conn->query($req); 

        return $res;
    }

}