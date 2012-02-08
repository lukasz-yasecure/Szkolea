<?php

/**
 * ogarnianie spraw zwiazanych z aktywowaniem kont
 * usuwanie kont nie aktywowanych na czas
 * usuwanie wpisow z tablicy z kodami aktywacji
 * tworzenie nowych kodow
 * wysylanie maila
 *
 * brakuje:
 *  SQLI
 *  BLEDY SQL
 *  weryfikowanie kodu aktywujacego - aktywowanie konta
 */
class Activation
{
    private $salt1 = '&Al7';
    private $salt2 = '$4Lt';
    private $db = null;

    private $uid = null;
    private $date_reg = null;
    private $mail = null;

    private $key = null;
    private $key2 = null;
    private $link = null;

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
            $db->set_charset('utf8');
            $this->db = $db;
        }
    }

    public function setUID($uid)
    {
        $this->uid = $uid;
    }

    public function setDateReg($dr)
    {
        $this->date_reg = $dr;
    }

    public function setEMail($m)
    {
        $this->mail = $m;
    }

    public function getActivateLink()
    {
        return $this->link;
    }

    /**
     * wywala z bazy konto ktore nie zostalo aktywowanie na czas wg. podanego user_id
     */
    public function deleteNotActivatedEMail()
    {
        if(is_null($this->db))
            $this->connectDB();

        if(!is_null($this->uid))
        {
            $sql = 'DELETE FROM users_324 WHERE id_user='.$this->uid;

            $this->db->query($sql);
        }
    }

    /**
     * wywala kod aktywujacy skojarzony z przeterminowanym kontem
     */
    public function deleteActivationKey()
    {
        if(is_null($this->db))
            $this->connectDB();

        if(!is_null($this->uid))
        {
            $sql = 'DELETE FROM users_activ WHERE id_user='.$this->uid;

            $this->db->query($sql);
        }
    }

    /**
     * oblicza nowy kod aktywujacy oraz kod kontrolny dla kodu aktywujacego
     */
    public function calcActivateKeys()
    {
        $this->key = md5($this->date_reg.$this->uid);
        $this->key2 = substr(md5($this->salt1.$this->key.$this->salt2), -10);
    }

    /**
     * zapisuje kod aktywujacy w bazie i wiaze go z podanym user_id
     */
    public function insertKeyInDB()
    {
        if(is_null($this->db))
            $this->connectDB();

        if(!is_null($this->key))
        {
            $sql = 'INSERT INTO users_activ (`id_user`, `key`) VALUES ('.$this->uid.', \''.$this->key.'\')';

            $this->db->query($sql);
        }
    }

    /**
     * wysylanie maila aktywujacego
     */
    public function sendActivateMail()
    {
        if(!is_null($this->mail))
        {
            $msg = 'http://szkolea.pl/szkolea/';
            $this->link = 'act.php?key='.$this->key.'&ckey='.$this->key2;
            $msg.= $this->link;

            $content = file_get_contents('view/html/mail_aktywacja.html');
            $content = str_replace('{%link%}', $msg, $content);

            $headers = 'From: szkolea.pl <no-reply@szkolea.pl>'."\r\n".'Reply-To: szkolea.pl <no-reply@szkolea.pl>'."\r\n";
            
            if($_SERVER['HTTP_HOST'] != 'localhost')
                mail($this->mail, 'Aktywacja konta szkolea.pl', $content, $headers);
            else
                $_SESSION['alink'] = $this->link;
        }
    }

    /**
     * na podstawie klucza glownego wyciaga UID
     * usuwa wpis aktywujacy
     * aktywuje konto wg. UID (0 -> 1)
     */
    public function validateMainKey(ActivControl $acc)
    {
        $this->connectDB();

        $sql = $acc->getValidateMainKeyQuery();

        $res = $this->db->query($sql);
        $res = $res->fetch_assoc();
        $id = $res['id_user'];

        $sql = $acc->getDeleteMainKeyFromDBQuery($id);

        $this->db->query($sql);

        $sql = $acc->getUpdateUserInDBQuery($id);

        $this->db->query($sql);
    }
}

?>