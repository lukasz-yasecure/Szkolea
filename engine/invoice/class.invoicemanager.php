<?php

class InvoiceManager {
    public function createUnpaidInvoiceProwizja(DBC $dbc, $ofe) {
    /* 
     * $ofe - dane wybranej oferty
     */
        $ile = $dbc->query(Query::getGroupCommUsers($ofe->id_comm))->num_rows; // liczba dopisanych do zlecenia osób
        $min_pr = '15'; // minimalna prowizja
        $pr = $ofe->cena * 0.15; // wyliczana prowizja
        if ($pr <= $min_pr) $sum_pr = $min_pr * $ile;
        if ($pr > $min_pr) $sum_pr = $pr * $ile;
        $dbc->query(Query::createUnpaidInvoice('1',$ofe->id_user,'NULL',$ofe->id_ofe,$sum_pr)); // tworzony nowy wpis faktury pro forma
    }

    public function createUnpaidInvoicePakiet(DBC $dbc,$user_id,$pk) {
    /* 
     * $pk - dane pakietu
     */
        $dbc->query(Query::createUnpaidInvoice('1',$user_id,$pk['id_pakietu'],'NULL',$pk['cena_brutto'])); // tworzony nowy wpis faktury pro forma
        BFEC::add('', true, 'profile.php?w=faktury&a=1&p='.$dbc->insert_id); // pobieranie id z mysqli, przekierowanie na formularz opłaty
    }
}

?>
