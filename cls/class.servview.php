<?php

class ServView
{
    private $vw = null;

    public function __construct()
    {
        $this->vw = new View();
    }

    public function getAddForm()
    {
        $content = file_get_contents('view/html/serv_add.html');
        $s = array(
            '{%cats%}',
            '{%wojs%}'
        );
        $r = array(
            $this->vw->getOptionsWithCategories(),
            $this->vw->getOptionsWithWojewodztwa()
        );
        $content = str_replace($s, $r, $content);
        return $content;
    }

    public function getServSite($t)
    {
        switch($t['cena_'])
        {
            case '1':
                $c = 'Bez Vat';
                break;
            case '2':
                $c = 'Zwolnione z Vat';
                break;
            case '3':
                $c = 'Z Vat 23%';
                break;
        }

        $a = new Auth();
        if(!$a->isLogged())
        {
            $ss = new SSMan();
            $ss->setRedirection('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $t['kontakt'] = 'Musisz się <a href="log.php">zalogować</a>';
        }

        $content = file_get_contents('view/html/serv.html');
        
        $s = array(
            '{%id%}',
            '{%nazwa%}',
            '{%koniec%}',
            '{%kategoria%}',
            '{%obszar%}',
            '{%tematyka%}',
            '{%termin%}',
            '{%miejsce%}',
            '{%cena%}',
            '{%kontakt%}'
        );

        $r = array(
            $t['id'],
            $t['nazwa'],
            date('Y-m-d H:m', $t['koniec']),
            $t['kategoria'],
            $t['obszar'],
            $t['tematyka'],
            isset($t['termin']) ? date('Y-m-d', $t['termin']['a']).' do '.date('Y-m-d', $t['termin']['b']) : 'do uzgodnienia',
            $t['miejsce'],
            $t['cena'].' ('.$c.')',
            $t['kontakt'],
        );

        $content = str_replace($s, $r, $content);
        
        $vw = new View();

        if(isset($t['info'])) $content = $vw->replaceVanishingCodeInTemplate($content, 'info', $t['info']);
        else $content = $vw->replaceVanishingCodeInTemplate($content, 'info');

        if(isset($t['moduly'])) $content = $vw->replaceVanishingCodeInTemplate($content, 'modul', $t['moduly']);
        else $content = $vw->replaceVanishingCodeInTemplate($content, 'modul');

        if(isset($t['program'])) $content = $vw->replaceVanishingCodeInTemplate($content, 'program', $t['program']);
        else $content = $vw->replaceVanishingCodeInTemplate($content, 'program');

        $content = $vw->clearLitter($content);

        return $content;
    }
}

?>
