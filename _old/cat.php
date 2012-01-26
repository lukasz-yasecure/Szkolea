<?php

require_once('config.php');
require_once('view/top.php');

$mc = new MainControl();
$tree = $mc->getCatTree();

/*
 * petla sie powtarza - w funkcje (tylko linki inne)
 * zle zlicza liczbe zlecen/uslug
 *
 */

if(isset($_GET['u']))
{
    foreach($tree as $a)
    {
        echo '<h1>'.$a['cat'].'</h1><br/>';

        foreach($a as $k => $b)
        {
            if($k == 'cat') continue;

            echo '<h3>'.$b['subcat'].'</h3><br/>';

            foreach($b as $k => $c)
            {
                if($k == 'subcat') continue;

                echo $c['subsubcat'].' ('.$mc->getCountOfComms($k).') <br/>';
            }
        }
    }
}
else if(isset($_GET['z']))
{
    // ZLECENIA
    foreach($tree as $a)
    {
        echo '<h1>'.$a['cat'].'</h1><br/>';

        foreach($a as $k => $b)
        {
            if($k == 'cat') continue;

            echo '<h3>'.$b['subcat'].'</h3><br/>';

            foreach($b as $k => $c)
            {
                if($k == 'subcat') continue;

                echo $c['subsubcat'].' ('.$mc->getCountOfComms($k).') <br/>';
            }
        }
    }
}
else
{
    // uslugodawcy
    echo ' katalog uslugodawcow';
}

require_once('view/foot.php');

?>