<?php

class View
{
    private $db = null;
    private $ss = null;
    private $msg = array(
        'log' => 'Zostałeś zalogowany!',
        'notlog' => 'Podane dane są niepoprawne!',
        'notlog2' => 'Podane dane są niepoprawne!',
        'notremind' => 'Podanego adresu nie ma w bazie!',
        'logout' => 'Zostałeś wylogowany!',
        'registered' => 'Twoje konto zostało pomyślnie zarejestrowane! Aby z niego skorzystać przeczytaj wiadomość, którą otrzymasz na adres mailowy podany przy rejestracji.',
        'aktyw' => 'Twoje konto wymaga aktywacji! Sprawdź maila...',
        'zle_add' => 'Zostałeś dopisany do zlecenia!',
        'zle_obs' => 'Obserwujesz zlecenie!',
        'zle_ofe' => 'Złożyłeś ofertę!',
        'dostawca' => 'Nie możesz dodawać usług ani składać ofert!',
        'klient' => 'Nie możesz dodawać zleceń!',
        'serv' => 'Niepoprawne dane!',
        'servadd' => 'Ogłoszenie zostało dodane!',
        'comm' => 'Niepoprawne dane w zleceniu!',
        'commadd' => 'Zlecenie zostało dodane!',
        'error' => 'Niespodziewany błąd! Spróbuj ponownie za chwilę...',
        'aktywowane' => 'Twoje konto zostało aktywowane! Może teraz się zalogować!',
        'nieaktywowane' => 'Twoje konto nie zostało aktywowane! Spróbuj ponownie później...',
        'offer' => 'Oferta została złożona!'
        );

    public function __construct()
    {
        $this->ss = new SSMan();
    }

    public function getSearchForm()
    {
        $form2 = '<div id="search_t_bottom"><div id="fields">';
        $form2.= '<div id="f_line_1"><div class="f_column_b1"><form action="index.php" method="post" name="searchForm">';
        $form2.= '<input type="hidden" name="search" value="1" />';
        $form2.= '<input type="radio" name="what" value="z" checked /> zlecenia</div><!-- f_column_b1 -->';
        $form2.= '<div class="f_column_b2"><input type="radio" name="what" value="u" /> usługi</div><!-- f_column_b2 --></div><!-- f_line_1 -->';
        $form2.= '<div id="f_line_2"><div class="f_column_a1"><span class="title_s">Kategoria</span>';
        $form2.= '<select name="cat" class="select_2" id="search_cat"><option value="0">wszystkie</option>'.$this->getOptionsWithCategories().'</select></div><!-- f_column_a1 -->';
        $form2.= '<div class="f_column_a2" id="kat2"><span class="title_s">Obszar</span><select name="subcat" class="select_2" id="search_subcat"><option value="0">wszystkie</option></select></div><!-- f_column_a2 -->';
        $form2.= '<div class="f_column_a3" id="kat3"><span class="title_s">Tematyka</span><select name="subsubcat" class="select_2" id="search_subsubcat"><option value="0">wszystkie</option></select></div><!-- f_column_a3 -->';
        $form2.= '</div><!-- f_line_2 --><div id="f_line_3"><div class="f_column_c1"><span class="title_s">Województwo</span><select name="woj" class="select_2"><option value="0">wszystkie</option>'.$this->getOptionsWithWojewodztwa().'</select>';
        $form2.= '</div><!-- f_column_c1 --><div class="f_column_c2"><span class="title_s">miasto</span><input type="text" name="place" class="input2"/></div><!-- f_column_c2 -->';
        $form2.= '<div class="f_column_c3"><span class="title_s">cena od</span><input type="text" name="cena_min" class="input2"/></div><!-- f_column_c3 -->';
        $form2.= '<div class="f_column_c4"><span class="title_s">cena do</span><input type="text" name="cena_max" class="input2"/></div><!-- f_column_c4 --></div><!-- f_line_3 -->';
        $form2.= '<div id="f_line_4"><div class="f_column_c1"><span class="title_s">kiedy</span>';
        $form2.= '<select name="dur0" class="select_2">';
        $form2.= '<option value="0">wszystkie</option>';
        $form2.= '<option value="1">dni robocze</option>';
        $form2.= '<option value="2">weekend</option>';
        $form2.= '<option value="3">dni wolne</option>';
        $form2.= '<option value="4">wieczorowo</option>';
        $form2.= '</select></div><!-- f_column_c1 -->';
        $form2.= '<div class="f_column_c2"><span class="title_s">jak długo</span>';
        $form2.= '<select name="dur1" class="select_dur1">';
        $form2.= '<option value="0">wszystkie</option>';
        $form2.= '<option value="1">1 dzień</option>';
        $form2.= '<option value="2">2 dni</option>';
        $form2.= '<option value="3">3 dni</option>';
        $form2.= '</select>';
        $form2.= '</div><!-- f_column_c2 -->';
        $form2.= '<div class="f_column_c3"><span class="title_s">liczebność od</span><input type="text" name="grupa_min" class="input2"/></div><!-- f_column_c3 -->';
        $form2.= '<div class="f_column_c4"><span class="title_s">liczebność do</span><input type="text" name="grupa_max" class="input2"/></div><!-- f_column_c4 --></div><!-- f_line_4 -->';
        $form2.= '<div id="f_line_5"><span class="title_s">Słowa w treści ogłoszenia:</span><br /><input name="word" class="input1" /></div><!-- f_line_5 -->';
        $form2.= '</div><!-- fields --><div id="butt_search"><button class="butt_search" type="submit" id="search_submit"></button></form></div><!-- butt_search --></div><!-- search_t_bottom -->';

        return $form2;
    }

    public function connectDB()
    {
        if(!is_null($this->db)) return true;
        
        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function getOptionsWithCategories($id = null)
    {
        $cats = $this->getAllCatsFromDB();

        $o1 = '<option value="id">cat</option>';
        $o2 = '<option value="id" selected>cat</option>';

        $ret = '';

        foreach($cats as $c)
        {
            if(!is_null($id) && $id == $c['id_cat'])
            {
                $ret.= str_replace(array('id', 'cat'), array($c['id_cat'], $c['cat']), $o2);
            }
            else
            {
                $ret.= str_replace(array('id', 'cat'), array($c['id_cat'], $c['cat']), $o1);
            }
        }

        return $ret;
    }

    public function getOptionsWithWojewodztwa()
    {
        $w = $this->getAllWojFromDB();

        $o = '<option value="id">cat</option>';

        $ret = '';

        foreach($w as $k => $v)
        {
            $ret.= str_replace(array('id', 'cat'), array($k, $v), $o);
        }

        return $ret;
    }

    public function getAllCatsFromDB()
    {
        if(is_null($this->db)) $this->connectDB();

        $sql = 'SELECT * FROM `cats_569` ORDER BY cat ASC';

        $res = $this->db->query($sql);

        $cats = array();

        while($t = $res->fetch_assoc())
        {
            $cats[] = $t;
        }

        return $cats;
    }

    public function getAllWojFromDB()
    {
        if(is_null($this->db)) $this->connectDB();

        $sql = 'SELECT * FROM `wojewodztwa` ORDER BY woj ASC';

        $res = $this->db->query($sql);

        $w = array();

        while($t = $res->fetch_assoc())
        {
            $w[$t['id_woj']] = $t['woj'];
        }

        return $w;
    }

    public function showMessages($errors = '')
    {
        while(($msg = $this->ss->getMessage()) != '')
        {
            echo '<em>'.$this->msg[$msg].'</em><br/>';
        }

        if($errors != '' && isset($_SESSION[$errors.'_errors']) && is_array($_SESSION[$errors.'_errors']) && count($_SESSION[$errors.'_errors']) > 0)
        {
            foreach($_SESSION[$errors.'_errors'] as $e)
            {
                echo '<em>'.$e.'</em>';
            }

            //unset($_SESSION[$errors.'_errors']);
        }
    }

    public function getDBIdOfModulFromID($id)
    {
        $this->connectDB();

        $sql = 'SELECT `id_modul` FROM `moduls_569` WHERE `id`=\''.$id.'\'';
        $res = $this->db->query($sql);
        $res = $res->fetch_assoc();

        return $res['id_modul'];
    }

    public function replaceVanishingCodeInTemplate($template, $var, $content = '')
    {
        /*
         * wzór templatki:
         *
         * {#.......{<li>%var%</li>}.....#}
         * 
         */
        if(preg_match('#\{\#([^\#]*?)%'.$var.'%(.*?)\#\}#s', $template, $m))
        {
            if($content != '')
            {
                if(preg_match('#\{([^{]*?)%'.$var.'%([^}]*?)\}#s', $m[0], $n))
                {
                    $new = '';

                    if(!is_array($content)) $content = array($content);

                    foreach($content as $mod)
                    {
                        $new.= $n[1].$mod.$n[2];
                    }

                    $template = str_replace($n[0], $new, $template);
                }
            }
            else
            {
                $template = str_replace($m[0], '', $template);
            }
        }

        return $template;
    }

    public function clearLitter($template)
    {
        $s2 = array('{#', '#}');
        $template = str_replace($s2, '', $template);

        return $template;
    }
}

?>
