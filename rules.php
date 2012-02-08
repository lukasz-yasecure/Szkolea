<?php

require_once('config_old.php');
require_once('view/top.php');

$content = file_get_contents('view/html/rules.html');
echo $content;

require_once('view/foot.php');

?>