<?php

class UMNoUser extends Exception
{
  public function __construct($id){
    parent::__construct('Brak usera o ID/email='.$id, 0);
  }
}

class UMTooManyUsers extends Exception
{
  public function __construct($id){
    parent::__construct('Dla ID/email='.$id.' jest wiecej niz 1 wynik', 0);
  }
}

class EmailIsNotAvailable extends Exception
{
  public function __construct($email){
    parent::__construct('Adres '.$email.' jest zajety', 0);
  }
}

class NoActivationKey extends Exception
{
  public function __construct($txt = ''){
    parent::__construct($txt, 0);
  }
}

class ActivationExpired extends Exception
{
  public function __construct($user_id){
    parent::__construct($user_id, 0);
  }
}

class InvalidUserValidation extends Exception
{
    public function __construct(){
    parent::__construct('', 0);
  }
}

/**
 * Tworzy obiekty typu User i wykonuje na nich wszystkie operacje:
 *  - wyciaga dane z bazy i pakuje je do obiektu User
 *
 *  2011-09-21  getUserByEmail() +
 *              getUserFromSession() +
 *              storeUserInSession() +
 *              updatePasswordInDB() +
 *  2011-09-22  DBQueryException() +
 *              checkIfEmailAvailable() +
 *              EmailIsNotAvailable +
 *  2011-09-26  activateUser
 *              deleteUser
 *              i nowe bledy
 *  2011-10-10  verifyUser + nowy exc
 */
class UserManager
{
    /**
     * Wyciaga z bazy uzytkownika o zadanym ID.
     * Moze sie wylozyc krytycznie na bledach bazy.
     *
     * @param DBC $dbc - obiekt polaczony z baza, wykonywanie zapytan
     * @param <type> $id - id usera ktorego chcemy
     * @return User
     * @throws DBQueryException jesli blad zapytania
     * @throws UMNoUser jesli nie ma usera w bazie (nie znaleziono ID)
     * @throws UMTooManyUsers jesli w bazie jest wiecej niz 1 user o zadanym ID
     */
    public function getUser(DBC $dbc, $id)
    {
        $sql = Query::getUser($id);
        $result = $dbc->query($sql); // false, true albo mysqli_result

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if($result->num_rows == 0) throw new UMNoUser($id);
        if($result->num_rows > 1) throw new UMTooManyUsers($id);

        $row = $result->fetch_assoc();

        $u = new User();
        $u->setId_user($row['id_user']);
        $u->setEmail($row['email']);
        $u->setPass($row['pass']);
        $u->setStatus($row['status']);
        $u->setDate_reg($row['date_reg']);
        $u->setKind($row['kind']);
        $u->setOs_name($row['os_name']);
        $u->setOs_surname($row['os_surname']);
        $u->setOs_street($row['os_street']);
        $u->setOs_house_number($row['os_house_number']);
        $u->setOs_postcode($row['os_postcode']);
        $u->setOs_city($row['os_city']);
        $u->setOs_woj($row['os_woj']);
        $u->setOs_phone($row['os_phone']);
        $u->setF_name($row['f_name']);
        $u->setF_surname($row['f_surname']);
        $u->setF_position($row['f_position']);
        $u->setF_company($row['f_company']);
        $u->setF_street($row['f_street']);
        $u->setF_house_number($row['f_house_number']);
        $u->setF_postcode($row['f_postcode']);
        $u->setF_city($row['f_city']);
        $u->setF_woj($row['f_woj']);
        $u->setF_regon($row['f_regon']);
        $u->setF_nip($row['f_nip']);
        $u->setF_krs($row['f_krs']);
        $u->setF_phone($row['f_phone']);

        $result->free();

        return $u;
    }

    /**
     * Pobiera uzytkownika zapisanego w sesji
     *
     * @param SessionManager $sm
     * @return User
     */
    public function getUserFromSession(SessionManager $sm)
    {
        return $sm->getUser();
    }

    /**
     * pobiera usera z bazy po adresie email
     *
     * @param DBC $dbc
     * @param string $email
     * @return User
     * @throws DBQueryException jesli blad zapytania
     * @throws UMNoUser jesli nie ma usera w bazie (nie znaleziono ID)
     * @throws UMTooManyUsers jesli w bazie jest wiecej niz 1 user o zadanym ID
     */
    public function getUserByEmail(DBC $dbc, $email)
    {
        $sql = Query::getUserByEmail($email);
        $result = $dbc->query($sql); // false, true albo mysqli_result

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if($result->num_rows == 0) throw new UMNoUser($email);
        if($result->num_rows > 1) throw new UMTooManyUsers($email);

        $row = $result->fetch_assoc();

        $u = new User();
        $u->setId_user($row['id_user']);
        $u->setEmail($row['email']);
        $u->setPass($row['pass']);
        $u->setStatus($row['status']);
        $u->setDate_reg($row['date_reg']);
        $u->setKind($row['kind']);
        $u->setOs_name($row['os_name']);
        $u->setOs_surname($row['os_surname']);
        $u->setOs_street($row['os_street']);
        $u->setOs_house_number($row['os_house_number']);
        $u->setOs_postcode($row['os_postcode']);
        $u->setOs_city($row['os_city']);
        $u->setOs_woj($row['os_woj']);
        $u->setOs_phone($row['os_phone']);
        $u->setF_name($row['f_name']);
        $u->setF_surname($row['f_surname']);
        $u->setF_position($row['f_position']);
        $u->setF_company($row['f_company']);
        $u->setF_street($row['f_street']);
        $u->setF_house_number($row['f_house_number']);
        $u->setF_postcode($row['f_postcode']);
        $u->setF_city($row['f_city']);
        $u->setF_woj($row['f_woj']);
        $u->setF_regon($row['f_regon']);
        $u->setF_nip($row['f_nip']);
        $u->setF_krs($row['f_krs']);
        $u->setF_phone($row['f_phone']);

        $result->free();

        return $u;
    }

    /**
     *
     * @param SessionManager $sm
     * @param User $u
     */
    public function storeUserInSession(SessionManager $sm, User $u)
    {
        $sm->storeUser($u);
    }

    /**
     *
     * @param DBC $dbc
     * @param User $u
     * @param PasswordChangeFormData $pcfd
     * @throws DBQueryException
     */
    public function updatePasswordInDB(DBC $dbc, User $u, PasswordChangeFormData $pcfd)
    {
        $sql = Query::getUpdatePassword($pcfd->getPass()->getHash(), $u->getId_user());
        $result = $dbc->query($sql); // false, true

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
    }

    /**
     *
     * @param DBC $dbc
     * @param RegisterFormData $rfd
     * @throws DBQueryException
     */
    public function storeNewUserInDB(DBC $dbc, RegisterFormData $rfd)
    {
        $sql = Query::storeNewUserInDB($rfd);
        $result = $dbc->query($sql); // false, true

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
    }

    /**
     *
     * @param DBC $dbc
     * @param string $email
     * @throws DBQueryException
     * @throws EmailIsNotAvailable
     */
    public function checkIfEmailAvailable(DBC $dbc, $email)
    {
        $sql = Query::getUserByEmail($email);
        $result = $dbc->query($sql); // false, true albo mysqli_result

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if($result->num_rows >= 1) throw new EmailIsNotAvailable($email); // email zajety
    }

    /**
     *
     * @param System $sys
     * @param DBC $dbc
     * @param ActivationMainKey $amk
     * @return int id usera
     * @throws DBQueryException
     * @throws NoActivationKey
     * @throws ActivationExpired
     */
    public function activateUser(System $sys, DBC $dbc, ActivationMainKey $amk)
    {
        $sql = Query::getUsersIDByAMK($amk->getMainKeyCode());
        $result = $dbc->query($sql);

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        if($result->num_rows != 1) throw new NoActivationKey();

        $row = $result->fetch_assoc();

        if($sys->getActivationExpiredDays()*(60*60*24) - (time() - $row['send_time']) < 0) throw new ActivationExpired($row['id_user']);

        $result->free();

        $sql = Query::activateUser($row['id_user']);
        $result = $dbc->query($sql);

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        return $row['id_user'];
    }

    /**
     *
     * @param DBC $dbc
     * @param int $id_user
     * @throws DBQueryException
     */
    public function deleteUser(DBC $dbc, $id_user)
    {
        $sql = Query::deleteUser($id_user);
        $result = $dbc->query($sql);

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
    }

    /**
     *
     * @param DBC $dbc
     * @param LoginFormData $lfd
     * @throws DBQueryException
     * @throws InvalidUserValidation
     */
    public function verifyUser(DBC $dbc, LoginFormData $lfd)
    {
        $sql = Query::getVerifyUser($lfd->getEmail(), $lfd->getPass()->getHash());
        $result = $dbc->query($sql);

        if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if($result->num_rows != 1) throw new InvalidUserValidation();
    }
}

?>