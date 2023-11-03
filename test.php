<?php
$output = [];
exec("tasklist /FI 'IMAGENAME eq php.exe' 2>&1", $output, $return_var);

if ($return_var === 0 && count($output) > 1) {
    echo "Tệp server.php đang chạy.";
} else {
    echo "Tệp server.php không đang chạy.";
}
?>
<script>
    
</script>