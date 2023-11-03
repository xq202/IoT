<?php
namespace Model;

use DAO\phpmysql;

class ActionModel{
    private $conn = null;
    public function __construct()
    {
        $this->conn = new phpmysql();
        $this->conn = $this->conn->Connect();
    }
    public function getListAction($start,$end,$index){
        $index*=20;
        $sql = "select * from action where 1 ";
        $s = '';
        if($start!=null){
            $s.=" and time >= '{$start}' ";
        }
        if($end!=null){
            $s.=" and time <= '{$end}' ";
        }
        // echo $sql;
        $stmt = $this->conn->stmt_init();
        $stmt->prepare($sql.$s."order by time desc limit {$index}, 20");
        $stmt->execute();
        $result = $stmt->get_result();
        $listData = [];
        if($result){
            while($row = $result->fetch_assoc()){
                $listData[] = $row;
            }
        }
        // echo 'select count(*) as sum from Action where 1 '.$s;
        $stmt->prepare('select count(*) as sum from Action where 1 '.$s);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['sum'];
        $list = [];
        $list['listAction'] = $listData;
        $list['count'] = $count;
        return $list;
    }
    public function addAction($device,$action){
        $stmt = $this->conn->stmt_init();
        $stmt->prepare('insert into action (device, action) values (?, ?)');
        $stmt->bind_param('ss',$device, $action);
        $stmt->execute();
        if($stmt->affected_rows>0){
            return 1;
        }
        else return 0;
    }
}