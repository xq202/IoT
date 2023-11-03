<?php
namespace Controller;

use Exception;
use Model\DataModel;
use MQTT\ConnectMQTT;

class WaitMessController{
    public function WaitMess(){
        $c = set_time_limit(10);
        $mqtt = new ConnectMQTT();
        $mqtt = $mqtt->connect();
        $topic = $_GET['topic'];
        $mqtt->subscribe([$topic=>["qos"=>0, "function"=>function($topic,$msg){
            echo $msg;
            exit();
        }]]);
        while($mqtt->proc()){
            
        }
        $mqtt->disconnect();
    }
}
$inp = new WaitMessController();
$inp->WaitMess();
?>