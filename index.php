<?php
require 'vendor/autoload.php';
function customAutoloader($className) {
    $Path = __DIR__.'\\';

    $FilePath = $Path . $className . '.php';

    if (file_exists($FilePath)) {
        require_once $FilePath;
    }
}
spl_autoload_register('customAutoloader');
session_start();
if(!isset($_GET["url"]) || $_GET['url']=='index.php'){
    require "./Controller/DashboardController.php";
    exit();
}
// echo $_GET('url');
$urls = explode('/',strtolower($_GET['url']));
$action = isset($urls[1]) ? $urls[1]:'';
switch($urls[0]){
    case "dashboard":
        require "./Controller/DashboardController.php";
        break;
    case 'sendmess':
        require './Controller/SendMessController.php';
        break;
    case 'waitmess':
        require './Controller/WaitMessController.php';
        break;
    case 'api':
        require './Controller/ApiController.php';
        break;
    default:
        echo 'index.php error';
        break;

}
