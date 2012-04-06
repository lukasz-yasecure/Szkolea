<?php

class InvoiceManager {

    public function createUnpaidInvoiceProwizja(DBC $dbc, $ofe) {
        /*
         * $ofe - dane wybranej oferty
         */
        $ile = $dbc->query(Query::getGroupCommUsers($ofe->id_comm))->num_rows; // liczba dopisanych do zlecenia osób
        $min_pr = '15'; // minimalna prowizja
        $pr = $ofe->cena * 0.15; // wyliczana prowizja
        if ($pr <= $min_pr)
            $sum_pr = $min_pr * $ile;
        if ($pr > $min_pr)
            $sum_pr = $pr * $ile;
        $dbc->query(Query::createUnpaidInvoice('1', $ofe->id_user, 'NULL', $ofe->id_ofe, $sum_pr)); // tworzony nowy wpis faktury pro forma
    }

    public function createUnpaidInvoicePakiet(DBC $dbc, $user_id, $pk) {
        /*
         * $pk - dane pakietu
         */
        $dbc->query(Query::createUnpaidInvoice('2', $user_id, $pk['id_pakietu'], 'NULL', $pk['cena_brutto'])); // tworzony nowy wpis faktury pro forma
        BFEC::add('', true, 'profile.php?w=faktury&a=1&p=' . $dbc->insert_id); // pobieranie id z mysqli, przekierowanie na formularz opłaty
    }

    /*     * Pobiera z bazy wszystkie faktury i zwraca ich tablicę jako obiektów
     *
     * @param DBC $dbc
     * @return Invoice tablica obiektów faktur
     */

    public function getInvoices(DBC $dbc) {
        $faktury = array();

        $sql = Query::getAllInvoice();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            return NULL;
        else {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $faktury[$i] = $this->getInvoiceFromRow($row);
                $i++;
            }
            return $faktury;
        }
    }

    /** Uzupełnianie obiektu Invoice (faktura) informacjami z pojedynczego wiersza z bazy danych
     *
     * @param type $row 1 wiersz z zapytania SQL
     * @return Invoice obiekt reprezentujący fakture
     */
    public function getInvoiceFromRow($row) {
        $fv = new Invoice();

        $fv->setId_faktura(isset($row['id_faktura']) ? $row['id_faktura'] : '-');
        $fv->setId_user(isset($row['id_user']) ? $row['id_user'] : '-');
        $fv->setTyp(isset($row['typ']) ? $row['typ'] : '-');
        $fv->setKwota_brutto(isset($row['kwota_brutto']) ? $row['kwota_brutto'] : '-');
        $fv->setId_pakiet(isset($row['id_pakiet']) ? $row['id_pakiet'] : '-');
        $fv->setId_oferta(isset($row['id_oferta']) ? $row['id_oferta'] : '-');
        $fv->setData_fv(isset($row['data_fv']) ? UF::timestamp2date($row['data_fv']) : '-');
        $fv->setNumer_fpf(isset($row['numer_fpf']) ? $row['numer_fpf'] : '-');
        $fv->setData_fpf(isset($row['data_fpf']) ? UF::timestamp2date($row['data_fpf']) : '-');
        $fv->setNumer_fv(isset($row['numer_fv']) ? $row['numer_fv'] : '-');

        return $fv;
    }

}

?>
