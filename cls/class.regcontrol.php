<?php

class RegControl
{
    private $a;
    private $source;

    private $ukind;
    private $email;
    private $pass;
    private $date_reg;
    private $os = array();
    private $f = array();

    public function __construct()
    {
        $this->a = new Auth();
    }

    public function getDateReg()
    {
        return $this->date_reg;
    }

    public function getEMail()
    {
        return $this->email;
    }

    public function checkPrivileges()
    {
        if($this->a->isLogged())
        {
            header('Location: index.php');
            exit();
        }
    }

    public function setSource($s)
    {
        $this->source = $s;
    }

    public function getAllDataFromSource()
    {
        if(is_null($this->source)) return false;
        
        if(!isset($this->source['ukind']) || !$this->checkUserKind($this->source['ukind'])) return false;
        if(!isset($this->source['email']) || !Valid::email($this->source['email'])) return false;
        else $this->email = $this->source['email'];
        if(!isset($this->source['pass1']) || !isset($this->source['pass2']) || !$this->checkPasses($this->source['pass1'], $this->source['pass2'])) return false;
        if(!isset($this->source['reg']) || $this->source['reg'] != '1') return false;

        // dane osobowe - wszystkie wymagane albo nic
        $os_error = 0;

        if(isset($this->source['os_name']) && Valid::name($this->source['os_name'])) $this->os['name'] = $this->source['os_name'];
        else $os_error++;

        if(isset($this->source['os_surname']) && Valid::surname($this->source['os_surname'])) $this->os['surname'] = $this->source['os_surname'];
        else $os_error++;

        if(isset($this->source['os_phone']) && Valid::phone($this->source['os_phone'])) $this->os['phone'] = $this->source['os_phone'];
        else $os_error++;

        if(isset($this->source['os_street']) && Valid::street($this->source['os_street'])) $this->os['street'] = $this->source['os_street'];
        else $os_error++;

        if(isset($this->source['os_house_number']) && Valid::house_number($this->source['os_house_number'])) $this->os['house_number'] = $this->source['os_house_number'];
        else $os_error++;

        if(isset($this->source['os_postcode']) && Valid::postcode($this->source['os_postcode'])) $this->os['postcode'] = $this->source['os_postcode'];
        else $os_error++;

        if(isset($this->source['os_city']) && Valid::city($this->source['os_city'])) $this->os['city'] = $this->source['os_city'];
        else $os_error++;

        // os_error
        // 0 - ok (sa dane obowiazkowe, teraz sprawdzimy opcjonalne)
        // 1-6 - error (blad na ktoryms obowiazkowym)
        // 7 - brak obowiazkowych wiec zadne opcjonalne nie moga byc uzupelnione

        if($os_error == 0)
        {
            if(isset($this->source['os_woj']) && Valid::woj($this->source['os_woj'])) $this->os['woj'] = $this->source['os_woj'];
        }
        else if($os_error >= 1 && $os_error <= 6) return false;
        else if($os_error == 7)
        {
            if(isset($this->source['os_woj']) && !empty($this->source['os_woj']))
            {
                return false;
            }
        }

        // dane firmowe - wszystkie wymagane
        $f_error = 0;

        if(isset($this->source['f_name']) && Valid::name($this->source['f_name'])) $this->f['name'] = $this->source['f_name'];
        else $f_error++;

        if(isset($this->source['f_surname']) && Valid::surname($this->source['f_surname'])) $this->f['surname'] = $this->source['f_surname'];
        else $f_error++;

        if(isset($this->source['f_position']) && Valid::position($this->source['f_position'])) $this->f['position'] = $this->source['f_position'];
        else $f_error++;

        if(isset($this->source['f_company']) && Valid::company($this->source['f_company'])) $this->f['company'] = $this->source['f_company'];
        else $f_error++;

        if(isset($this->source['f_street']) && Valid::street($this->source['f_street'])) $this->f['street'] = $this->source['f_street'];
        else $f_error++;

        if(isset($this->source['f_house_number']) && Valid::house_number($this->source['f_house_number'])) $this->f['house_number'] = $this->source['f_house_number'];
        else $f_error++;

        if(isset($this->source['f_postcode']) && Valid::postcode($this->source['f_postcode'])) $this->f['postcode'] = $this->source['f_postcode'];
        else $f_error++;

        if(isset($this->source['f_city']) && Valid::city($this->source['f_city'])) $this->f['city'] = $this->source['f_city'];
        else $f_error++;

        if(isset($this->source['f_phone']) && Valid::phone($this->source['f_phone'])) $this->f['phone'] = $this->source['f_phone'];
        else $f_error++;

        if(isset($this->source['f_nip']) && ($nip = Valid::nip($this->source['f_nip']))) $this->f['nip'] = $nip;
        else $f_error++;

        // f_error
        // 0 - ok
        // 1-9 - error
        // 10 - brak obowiazkowych

        if($f_error == 0)
        {
            if(isset($this->source['f_woj']) && Valid::woj($this->source['f_woj'])) $this->f['woj'] = $this->source['f_woj'];
            if(isset($this->source['f_regon']) && Valid::regon($this->source['f_regon'])) $this->f['regon'] = $this->source['f_regon'];
            if(isset($this->source['f_krs']) && Valid::krs($this->source['f_krs'])) $this->f['krs'] = $this->source['f_krs'];
        }
        else if($f_error >= 1 && $f_error <= 9) return false;
        else if($f_error == 10)
        {
            if((isset($this->source['f_woj']) && !empty($this->source['f_woj'])) || (isset($this->source['f_krs']) && !empty($this->source['f_krs'])) || (isset($this->source['f_regon']) && !empty($this->source['f_regon'])))
            {
                return false;
            }
        }

        // nie podano zadnych danych - ani osobowych ani firmowych - error
        if($os_error == 7 && $f_error == 10) return false;

        return true;
    }

    private function checkUserKind($k)
    {
        switch($k)
        {
            case 'K':
                $this->ukind = 'K';
                return true;
            case 'D':
                $this->ukind = 'D';
                return true;
            default:
                return false;
        }
    }

    private function checkPasses($p1, $p2)
    {
        if($p1 !== $p2) return false;
        if(strlen($p1) < 6) return false;

        $this->pass = $p1;
        return true;
    }

    public function getCheckQueryIfEMailInDB()
    {
        if(is_null($this->email)) return false;

        $sql = 'SELECT id_user, status, date_reg FROM users_324 WHERE email=\''.$this->email.'\'';

        return $sql;
    }

    public function getRegisterQuery()
    {
        $this->date_reg = time();

        $sql = "INSERT INTO `users_324` (
                    `id_user` ,
                    `email` ,
                    `pass` ,
                    `status` ,
                    `date_reg` ,
                    `kind` ,
                    `os_name` ,
                    `os_surname` ,
                    `os_street` ,
                    `os_house_number` ,
                    `os_postcode` ,
                    `os_city` ,
                    `os_woj` ,
                    `os_phone` ,
                    `f_name` ,
                    `f_surname` ,
                    `f_position` ,
                    `f_company` ,
                    `f_street` ,
                    `f_house_number` ,
                    `f_postcode` ,
                    `f_city` ,
                    `f_woj` ,
                    `f_regon` ,
                    `f_nip` ,
                    `f_krs` ,
                    `f_phone`
                    )
                VALUES (
                    NULL ,
                    '".$this->email."',
                    SHA1( '".$this->pass."' ) ,
                    '0',
                    '".$this->date_reg."',
                    '".$this->ukind."',
                    '".(isset($this->os['name']) ? $this->os['name'] : "NULL")."',
                    '".(isset($this->os['surname']) ? $this->os['surname'] : "NULL")."',
                    '".(isset($this->os['street']) ? $this->os['street'] : "NULL")."',
                    '".(isset($this->os['house_number']) ? $this->os['house_number'] : "NULL")."',
                    '".(isset($this->os['postcode']) ? $this->os['postcode'] : "NULL")."',
                    '".(isset($this->os['city']) ? $this->os['city'] : "NULL")."',
                    '".(isset($this->os['woj']) ? $this->os['woj'] : "NULL")."',
                    '".(isset($this->os['phone']) ? $this->os['phone'] : "NULL")."',
                    '".(isset($this->f['name']) ? $this->f['name'] : "NULL")."',
                    '".(isset($this->f['surname']) ? $this->f['surname'] : "NULL")."',
                    '".(isset($this->f['position']) ? $this->f['position'] : "NULL")."',
                    '".(isset($this->f['company']) ? $this->f['company'] : "NULL")."',
                    '".(isset($this->f['street']) ? $this->f['street'] : "NULL")."',
                    '".(isset($this->f['house_number']) ? $this->f['house_number'] : "NULL")."',
                    '".(isset($this->f['postcode']) ? $this->f['postcode'] : "NULL")."',
                    '".(isset($this->f['city']) ? $this->f['city'] : "NULL")."',
                    '".(isset($this->f['woj']) ? $this->f['woj'] : "NULL")."',
                    '".(isset($this->f['regon']) ? $this->f['regon'] : "NULL")."',
                    '".(isset($this->f['nip']) ? $this->f['nip'] : "NULL")."',
                    '".(isset($this->f['krs']) ? $this->f['krs'] : "NULL")."',
                    '".(isset($this->f['phone']) ? $this->f['phone'] : "NULL")."'
                )";

        return str_replace("'NULL'", 'NULL', $sql);
    }
}

?>
