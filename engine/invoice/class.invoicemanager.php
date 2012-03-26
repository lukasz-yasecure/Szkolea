<?php

class InvoiceManager {
    public function createUnpaidInvoiceIM(DBC $dbc,Mailer $m,$ofe) {
    /* 
     * $ofe - dane wybranej oferty
     */
        $ile = $dbc->query(Query::getGroupCommUsers($ofe->id_comm))->num_rows; // liczba dopisanych do zlecenia osób
        $min_pr = '15'; // minimalna prowizja
        $pr = $ofe->cena * 0.15; // wyliczana prowizja
        if ($pr <= $min_pr) $sum_pr = $min_pr * $ile;
        if ($pr > $min_pr) $sum_pr = $pr * $ile;
        $dbc->query(Query::createUnpaidInvoiceDB($ofe->id_user,$ofe->id_ofe,$sum_pr)); // tworzony nowy wpis faktury
    }
}

?>
