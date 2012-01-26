<?php
require_once('config_old.php');
require_once('view/top.php');

echo BFEC::showAll();

$content = file_get_contents('view/html/index.html');
$logbar = '';

if(!$a->isLogged()) $logbar = file_get_contents('view/html/index_not_logged.html');
else
{
    $logbar = file_get_contents('view/html/index_logged.html');
    $logbar = str_replace('{%name%}', $ss->getEMail(), $logbar);
}

$iv = new IndexView();
$vw = new View();

$s = array(
    '{%logbar%}',
    '{%kategorie%}',
    '{%wojewodztwa%}',
    '{%leftMenu%}',
    '{%wyniki%}'
);
$r = array(
    $logbar,
    $vw->getOptionsWithCategories(),
    $vw->getOptionsWithWojewodztwa(),
    $iv->getLeftMenu(),
    $iv->getResults()
);

echo str_replace($s, $r, $content);

require_once('view/foot.php');
?>
