<?php

/**
 *  2011-11-08  klasa obslugujaca wyniki do wyswietlenia, wybiera odpowiednie zapytanie do bazy i zwraca obiekt z wynikami do wyswietlenia
 */
class ResultsManager
{
    /**
     * tworzy obiekt Results ktory zawiera odpowiednio Commisions / Services do wyswietlenia w indeksie
     *
     * @param DBC $dbc
     * @param Search $s
     * @return Results
     */
    public function getResults(DBC $dbc, Search $s, $sql = null)
    {
        $r = new Results();

        if($s->getWhat() == 'comms')
        {
            $cm = new CommisionManager();
            $r->setCommisions();

            if(is_null($sql))
            {
                if($s->getAll()) $sql = Query::getAllCommisions();
                else if(!is_null($s->getKot_id())) $sql = Query::getCommisionsForLeftMenu($s);
                else $sql = Query::getCommisionsForSearch($s);
            }
            
            $result = $dbc->query($sql);
            if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            while($row = $result->fetch_assoc())
            {
                $r->addComm($cm->getCommisionFromRow($row));
            }
        }
        else if($s->getWhat() == 'servs')
        {
            $sm = new ServiceManager();
            $r->setServices();

            if(is_null($sql))
            {
                if($s->getAll()) $sql = Query::getAllServices();
                else if(!is_null($s->getKot_id())) $sql = Query::getServicesForLeftMenu($s);
                else $sql = Query::getServicesForSearch($s);
            }

            $result = $dbc->query($sql);
            if(!$result) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            while($row = $result->fetch_assoc())
            {
                $r->addServ($sm->getServiceFromRow($row));
            }
        }

        return $r;
    }
}

?>
