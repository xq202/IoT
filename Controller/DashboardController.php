<?php
namespace Controller;
use MQTT\ConnectMQTT;
use SocketServer\GetSocketServer;

class DashboardController{
    public function View(){
        echo "<base href=\"/IoT/\">";
        $mqtt = new ConnectMQTT();
        $mqtt = $mqtt->connect();
        $mqtt->publish('status','getStatus');
        $data = $mqtt->subscribeAndWaitForMessage("status",0);
        $listData = [];
        $listData = json_decode($data,true);
        $ledon = intval($listData['led']);
        $ledon1 = intval($listData['led1']);
        $fanon = intval($listData['fan']);
        require './View/Dashboard.php';
    }
}

$view = new DashboardController();
switch($action){
    default:
        $view->View();
        break;
}
?>