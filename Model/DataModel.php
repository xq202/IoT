<?php
namespace Model;

use DAO\phpmysql;

class DataModel{
    private $conn = null;
    public function __construct()
    {
        $this->conn = new phpmysql();
        $this->conn = $this->conn->Connect();
    }
    public function getListData($index,$start,$end,$doBui,$nhietDo,$doAm,$anhSang,$sortBy,$optionSort){
        $index*=20;
        $stmt = $this->conn->stmt_init();
        $sql = 'select * from Data where 1 ';
        $s = '';
        if($start != null){
            $s.="and time>='{$start}' ";
        }
        if($end != null){
            $s.=" and time<='{$end}'";
        }
        if($nhietDo!=null){
            $s.="and temperature like '{$nhietDo}%' ";
        }
        if($doBui!=null){
            $s.="and dobui like '{$doBui}%' ";
        }
        if($doAm!=null){
            $s.="and humidity like '{$doAm}%' ";
        }
        if($anhSang!=null){
            $s.=" and blightness like '{$anhSang}%' ";
        }
        $s.=" order by {$sortBy} {$optionSort}";
        // echo $sql.$s;
        $stmt->prepare($sql.$s." limit {$index},20");
        $stmt->execute();
        $result = $stmt->get_result();
        $listData = [];
        if($result){
            while($row = $result->fetch_assoc()){
                $listData[] = $row;
            }
        }
        $stmt->prepare('select count(*) as sum from Data where 1 '.$s);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['sum'];
        $list = [];
        $list['listData'] = $listData;
        $list['count'] = $count;
        return $list;
    }
    public function addData($doBui,$temperature,$humidity,$blightness){
        $stmt = $this->conn->stmt_init();
        $stmt->prepare('insert into Data (dobui, temperature, humidity, blightness) values (?, ?, ?, ?)');
        $stmt->bind_param('ssss',$doBui,$temperature,$humidity,$blightness);
        $stmt->execute();
        $result = $stmt->get_result();
        if($stmt->affected_rows>0){
            return 1;
        }
        else return 0;
    }

}