<?php

class ActivControl
{
    private $source;
    private $key1;
    private $key2;
    private $salt1 = '&Al7';
    private $salt2 = '$4Lt';

    public function setSource($s)
    {
        $this->source = $s;
    }

    public function getKey()
    {
        return $this->key1;
    }

    public function getControlKey()
    {
        return $this->key2;
    }

    public function getKeysFromSource()
    {
        if(isset($this->source['key'], $this->source['ckey']))
        {
            if(empty($this->source['key']) || empty($this->source['ckey']))
                return false;
            else
            {
                $this->key1 = $this->source['key'];
                $this->key2 = $this->source['ckey'];
                return true;
            }
        }
    }

    public function validateControlKey()
    {
        $temp = substr(md5($this->salt1.$this->key1.$this->salt2), -10);

        if($temp == $this->key2)
            return true;
        else
            return false;
    }

    public function getValidateMainKeyQuery()
    {
        $sql = 'SELECT `id_user` FROM `users_activ` WHERE `key`=\''.$this->key1.'\'';

        return $sql;
    }

    public function getDeleteMainKeyFromDBQuery($id)
    {
        $sql = 'DELETE FROM `users_activ` WHERE `id_user` = '.$id;

        return $sql;
    }

    public function getUpdateUserInDBQuery($id)
    {
        $sql = 'UPDATE `users_324` SET `status` = \'1\' WHERE `id_user`='.$id;

        return $sql;
    }
}

?>
