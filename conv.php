<?php

$gc_log = fopen("gc_test.log", "a");
fwrite($gc_log, $_GET['mytoken']."\n\r");
fclose($gc_log);

print $_GET['mytoken'] . " ok";

?>