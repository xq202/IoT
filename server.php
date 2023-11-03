<?php

use Bluerhinos\phpMQTT;
use Exception;
use Model\DataModel;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
require "vendor/autoload.php";
require "Model/DataModel.php";
require "DAO/phpmysql.php";
class SocKetMQTT implements MessageComponentInterface{
    protected $connections = [];
    protected $address;
    public function __construct() {
        
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections[] = $conn;
        echo "New connection! ({$conn->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $mqtt = new phpMQTT($this->address,1883,"quangtx".base64_encode(random_bytes(3)));
        try{
            $mqtt->connect();
            echo "mqtt connect\n";
            if($msg=="data"){
                $mess = $mqtt->subscribeAndWaitForMessage("data",0);
                echo $mess."\n";
                $from->send($mess);
                $data = json_decode($mess,true);
                // print_r(($data));
                $dataModel = new DataModel();
                $c = $dataModel->addData($data['doBui'],$data['temperature'],$data['humidity'],$data['blightness']);
                if($c==0) echo 'addData error';
                ob_flush();
                flush();
            }
        }
        catch(Exception $e){
            echo $e;
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        $key = array_search($conn, $this->connections);
        if($key!=false){
            unset($this->connections[$key]);
        }
    }
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo $e;
        ob_flush();
        flush();
        $conn->close();
    }
}
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
set_time_limit(0);
$server = IoServer::factory(new HttpServer(new WsServer(new SocKetMQTT())),8888,"127.0.0.1");
echo "web socket server running!\n";
// ob_flush();
flush();
// set_time_limit(60);
$server->run();
