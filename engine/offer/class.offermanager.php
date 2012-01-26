<?php

/**
 *  2011-11-09  na razie tylko zliczanie ofert dla konkretnego zlecenia
 */
class OfferManager
{
    public function getOfferCountForCommision(DBC $dbc, Commision $c)
    {
        $cid = $c->getId_comm();

        if(is_null($cid)) return null;

        $sql = Query::getOfferCountForCommision($cid);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        $res = $res->fetch_assoc();

        return $res['ile'];
    }
}

?>
