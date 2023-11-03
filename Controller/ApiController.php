<?php
namespace Controller;

use Model\ActionModel;
use Model\DataModel;
use MQTT\ConnectMQTT;

class ApiController{
    public function GetListAction(){
        $actionModel = new ActionModel();
        $index = $_GET['index'];
        $start = (isset($_GET['start'])) ? json_decode($_GET['start']) : null;
        $end = (isset($_GET['end'])) ? json_decode($_GET['end']) : null;
        $listAction = $actionModel->getListAction($start,$end,$index);
        echo json_encode($listAction);
    }
    public function addAction(){
        $action = json_decode($_GET['action']);
        $device = json_decode($_GET['device']);
        $actionModel = new ActionModel();
        $res = $actionModel->addAction($device, $action);
        if(!$res){
            echo "<script>alert('addAction error');</script>";
        }
    }
    public function getListData(){
        $index = $_GET['index'];
        $start = json_decode(isset($_GET['start']) ? $_GET['start'] : null);
        $end = json_decode(isset($_GET['end']) ? $_GET['end'] : null);
        $nhietDo = isset($_GET['nhietdo']) ? $_GET['nhietdo'] : null;
        $doAm = isset($_GET['doam']) ? $_GET['doam'] : null;
        $doBui = isset($_GET['dobui']) ? $_GET['dobui'] : null;
        $anhSang = isset($_GET['anhsang']) ? $_GET['anhsang'] : null;
        $sortBy = $_GET['sortby'];
        $optionSort = $_GET['optionsort'];
        $dataModel = new DataModel();
        $listData = $dataModel->getListData($index,$start,$end,$doBui,$nhietDo,$doAm,$anhSang,$sortBy,$optionSort);
        echo json_encode($listData);
    }
    public function addData(){
        $data = json_decode($_POST['data'],true);
        // print_r(($data));
        $dataModel = new DataModel();
        $c = $dataModel->addData($data['doBui'],$data['temperature'],$data['humidity'],$data['blightness']);
        if($c==0) echo 'addData error';
    }
    public function deviceControl(){
        set_time_limit(10);
        $mqtt = new ConnectMQTT();
        $mqtt = $mqtt->connect();
        $led = $_GET['led'];
        $fan = $_GET['fan'];
        $led1 = $_GET['led1'];
        $listData = [];
        $listData['led'] = $led;
        $listData['led1'] = $led1;
        $listData['fan'] = $fan;
        $mqtt->publish('controller',json_encode($listData));
        $resp = $mqtt->subscribeAndWaitForMessage("responses",0);
        echo $resp;
    }
    public function sendMess(){
        $topic = $_GET['topic'];
        $mess = json_decode($_GET['mess']);
        $mqtt = new ConnectMQTT();
        $mqtt = $mqtt->connect();
        $mqtt->publish($topic,$mess);
    }
}

$apiController = new ApiController();
switch($action){
    case 'getlistaction':
        $apiController->GetListAction();
        break;
    case 'getlistdata':
        $apiController->GetListData();
        break;
    case 'getlistaction':
        $apiController->GetListAction();
        break;
    case 'addaction':
        $apiController->addAction();
        break;
    case 'adddata':
        $apiController->addData();
        break;
    case 'devicecontrol':
        $apiController->deviceControl();
        break;
    case "sendmess":
        $apiController->sendMess();
        break;
}