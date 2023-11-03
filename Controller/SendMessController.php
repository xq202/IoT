<?php
$i = 0;
while(1){
    $x = shell_exec('mosquitto_pub -t data -m "{\"temperature\": '.(Rand()/getrandmax()*100).', \"humidity\": '.(Rand()/getrandmax()*100).', \"blightness\":'.(Rand()/getrandmax()*1000).'}"');
    $i++;
    sleep(1.5);
}

// echo base64_encode(random_bytes(3));