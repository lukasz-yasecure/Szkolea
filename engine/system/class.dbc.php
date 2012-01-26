<?php

/**
 * polaczenie z baza, operacje na bazie
 *
 *  2011-09-21 ostatni wglad
 */
class DBC extends mysqli
{
    /**
     * Polaczenie z baza. Handshake.
     *
     * @param System $sys stad bierzemy dane do bazy
     * @throws DBConnectException jesli nie uda sie polaczyc z baza
     * @throws DBCharsetException jesli nie ustawi kodowania
     */
    public function  __construct(System $sys)
    {
        $this->init();
        if(!@parent::real_connect($sys->getDb_host(), $sys->getDb_login(), $sys->getDb_pass(), $sys->getDb_dbname())) throw new DBConnectException($this->connect_error, $this->connect_errno);
        if(!@parent::set_charset('utf8')) throw new DBCharsetException($this->connect_error, $this->connect_errno);
    }

    /**
     * Loguje zapytanie.
     * Wykonuje zapytanie na bazie danych. SELECT SHOW DESCRIBE EXPLAIN zwraca mysqli_result, reszta TRUE. Jesli blad to FALSE.
     * Jesli wynik to FALSE to rzuca wyjatek.
     *
     * @param string $sql zapytanie sql
     * @return mysqli_result bool
     */
    public function query($sql)
    {
        Log::SqlQuery($sql);
        $result = @parent::query($sql);
        return $result;
    }
}

?>
