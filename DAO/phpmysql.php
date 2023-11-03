<?php
namespace DAO;

use Exception;
use mysqli;

class phpmysql{
    private $user = 'root';
    private $pass = null;
    private $db_server = 'localhost';
    private $db_name = 'iot';
    public function Connect(){
        $conn = null;
        try{
            $conn = mysqli_connect($this->db_server,$this->user,$this->pass,$this->db_name);

        }

        catch(Exception $e){
            echo $e;
        }
        return $conn;
    }
}