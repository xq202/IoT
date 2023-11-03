<?php
namespace MQTT;
use Bluerhinos\phpMQTT;
use Exception;
use PhpMqtt\Client\MqttClient;

class ConnectMQTT{
    private $mqtt_server = "localhost";
    private $mqtt_port = 1883;
    private $mqtt_username = "quangtx";
    private $mqtt_password = "2002";
    private $mqtt_client_id = 'quangtx';
    public function __construct()
    {
        $this->mqtt_client_id .=base64_encode(random_bytes(3));
    }
    public function connect(){
        $mqtt = new phpMQTT($this->mqtt_server,$this->mqtt_port,$this->mqtt_client_id);
        try{
            $mqtt->connect();
            // $mqtt->publish($topic, $message, 0);
            // $mqtt->close();
            return $mqtt;
        }
        catch(Exception $e){
            echo "Kết nối thất bại!"+$e;
        }
    }
}