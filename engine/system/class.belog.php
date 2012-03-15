<?php

/**
 * Behavior Log - klasa do logowania zachowan uzytkownikow 
 */
class BeLog {
    public static function saveUserLogin(DBC $dbc, $email) {
        $dbc->query(Query::getSaveUserLogin($email));
        return $dbc->insert_id;
    }
    
    public static function updateUserLoginToSuccess($dbc, $id) {
        $dbc->query(Query::updateUserLoginToSuccess($id));
    }
}

?>
