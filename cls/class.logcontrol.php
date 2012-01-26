<?php

class LogControl
{
    private $a;
    private $v;

    private $email;
    private $pass;

    public function __construct()
    {
        $this->a = new Auth();
        $this->v = new Valid();
    }

    public function checkPrivileges()
    {
        /*
         * przy logowaniu sprawdzamy czy juz nie jest czasem zalogowany (jesli tak - nara)
         */

        if($this->a->isLogged())
        {
            header('Location: index.php');
            exit();
        }
    }

    public function getAllDataFromSource($source)
    {
        /*
         * sprawdzamy poprawność danych
         * bledy:
         *  - source to nie tablica
         *  - source nie ma 3 elementow
         *  - nie podano emaila
         *  - email jest niepoprawny
         *  - nie podano passa
         *  - pass jest niepoprawny
         * Nie ma dokladnego info o rodzaju bledu - po prostu Niepoprawne dane
         */

        if(!is_array($source) || count($source) != 3) return false;
        if(!isset($source['email']) || !$this->v->email($source['email'])) return false;
        else
        {
            $this->email = $source['email'];
            RFD::add('logForm', 'email', $this->email);
        }
        if(!isset($source['pass'])) return false;
        else $this->pass = $source['pass'];

        return true;
    }

    public function getEMailFromSource($source)
    {
        /*
         * cos jak getAll tylko ze sam email do reminda!
         */

        if(!is_array($source) || count($source) != 2) return false;
        if(!isset($source['email']) || !$this->v->email($source['email'])) return false;
        else
        {
            $this->email = $source['email'];
        }

        return true;
    }

    public function getLogQuery()
    {
        $sql = 'SELECT * FROM users_324 WHERE email=\''.$this->email.'\' AND pass=SHA1(\''.$this->pass.'\')';

        return $sql;
    }

    public function getQueryForEMailExistanceInDB()
    {
        $sql = 'SELECT id_user, date_reg FROM users_324 WHERE email=\''.$this->email.'\'';

        return $sql;
    }

    public function getEmail()
    {
        return $this->email;
    }
}

?>
