<?php

class NoPreparedActivationKeys extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

class WrongRemindKey extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class InvalidKey extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

/**
 * tworzy nowe obiekty kluczy:
 *  - aktywacyjnych (main i control)
 *  - remindujacy
 *
 *  2011-09-21  getRemindKey() +
 *              checkRemindKey() +
 *  2011-09-26  ext InvalidKey
 *              checkActivationControlKey
 *              deleteKeyForUser
 */
class KeyManager
{
    private $amk; // ActivationMainKey
    private $ack; // ActivationControlKey

    /**
     *
     * @param User $u
     * @return boolean
     */
    public function prepareActivationKeys(User $u)
    {
        $amk = new ActivationMainKey($u);
        $this->amk = $amk;
        $ack = new ActivationControlKey($amk);
        $this->ack = $ack;
        return true;
    }
    
    /**
     *
     * @return ActivationMainKey
     * @throws NoPreparedActivationKeys jesli nie przygotowano wczesniej kluczy
     */
    public function getActivationMainKey()
    {
        if(is_null($this->amk))
            throw new NoPreparedActivationKeys('Klucze nie zostały przygotowane!');
        else
            return $this->amk;
    }

    /**
     *
     * @return ActivationControlKey
     * @throws NoPreparedActivationKeys jesli nie przygotowano wczesniej kluczy
     */
    public function getActivationControlKey()
    {
        if(is_null($this->ack))
            throw new NoPreparedActivationKeys('Klucze nie zostały przygotowane!');
        else
            return $this->ack;
    }

    /**
     *
     * @param DBC $dbc
     * @param User $u
     * @throws NoPreparedActivationKeys
     * @throws DBQueryException
     */
    public function storeActivationKey(DBC $dbc, User $u)
    {
        if(is_null($this->amk))
            throw new NoPreparedActivationKeys('Klucze nie zostały przygotowane!');

        $sql = Query::storeActivationKey($u->getId_user(), $this->getActivationMainKey()->getMainKeyCode());
        if(!$dbc->query($sql))
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
    }

    /**
     *
     * @param User $u
     * @return RemindKey 
     */
    public function getRemindKey(User $u)
    {
        $rk = new RemindKey($u);
        return $rk;
    }

    /**
     *
     * @param RemindMailData $rmd
     * @param User $u
     * @throws WrongRemindKey
     */
    public function checkRemindKey(RemindMailData $rmd, User $u)
    {
        $key = $rmd->getKey();
        $rk = new RemindKey($u);

        if($key != $rk->getKeyCode()) throw new WrongRemindKey('');
    }

    /**
     *
     * @param ActivationControlKey $ack
     * @param string $ack_code
     * @throws InvalidKey
     */
    public function checkActivationControlKey(ActivationControlKey $ack, $ack_code)
    {
        if($ack->getControlKeyCode() != $ack_code) throw new InvalidKey('');
    }

    /**
     *
     * @param DBC $dbc
     * @param int $id_user
     * @throws DBQueryException
     */
    public function deleteKeyForUser(DBC $dbc, $id_user)
    {
        $sql = Query::deleteKeyForUser($id_user);
        $result = $dbc->query($sql);

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
    }
}

?>