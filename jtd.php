<?php

require_once('config.php');
require_once('view/top.php');

$content = file_get_contents('view/html/jtd.html');
echo $content;

require_once('view/foot.php');

?>