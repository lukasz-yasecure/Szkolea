<?php

class ServiceManager
{
    /**
     * Przy dodawaniu uslugi przez usera trzeba uzupelnic troche dodatkowych danych
     * a niektore skonwertowac - taki ostatni etap przed wprowadzeniem do bazy
     *
     * @param Service $s
     * @param DBC $dbc
     * @param CategoryManager $cm
     * @return Service
     */
    public function completeData(Service $s, DBC $dbc, CategoryManager $cm)
    {
        try
        {
            $kategoria_name = $cm->getNameOfKategoria($dbc, $s->getCat());
            $obszar_name = $cm->getNameOfObszar($dbc, $s->getSubcat());
            $tematyka_name = $cm->getNameOfTematyka($dbc, $s->getSubsubcat());
            $moduly_names = '';

            $moduly = $s->getModuly();
            if(is_array($moduly))
            {
                $temp = array();
                foreach($moduly as $m)
                {
                    $temp[] = $cm->getNameOfModul($dbc, $m);
                }

                $moduly_names = implode(',', $temp);
            }

            $s->setKategoria_name($kategoria_name);
            $s->setObszar_name($obszar_name);
            $s->setTematyka_name($tematyka_name);
            $s->setModuly_names($moduly_names);
            $s->setKotm($kategoria_name.', '.$obszar_name.', '.$tematyka_name.', '.$moduly_names);
            $s->setDate_a(UF::date2timestamp($s->getDate_a()));
            $s->setDate_b(UF::date2timestamp($s->getDate_b()));
        }
        catch(Exception $e)
        {
            throw $e;
        }

        return $s;
    }

    /**
     * Dodawanie Service i powiazanych modulow do bazy (2 tabele atakowane)
     *
     * @param DBC $dbc
     * @param Service $s
     * @return Service 
     */
    public function saveServiceInDB(DBC $dbc, Service $s)
    {
        $sql = Query::saveNewServiceInDB($s);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);

        $s->setId_serv($dbc->insert_id);
        if(!is_null($s->getModuly()))
        {
            $sql = Query::saveModulsForService($s);
            $res = $dbc->query($sql);
            if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        }

        return $s;
    }

    /**
     * pobieranie konkretnego Service z bazy danych
     *
     * @param DBC $dbc
     * @param int $id
     * @return Service
     */
    public function getService(DBC $dbc, $id)
    {
        $sql = Query::getService($id);
        $res = $dbc->query($sql);
        if(!$res) throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        return $this->getServiceFromRow($res->fetch_assoc());
    }

    public function getServiceFromRow($row)
    {
        $s = new Service();
        $s->setId_serv($row['id_serv']);
        $s->setId_user($row['id_user']);
        $s->setDate_add($row['date_add']);
        $s->setDate_end($row['date_end']);
        $s->setName($row['name']);
        $s->setProgram($row['program']);
        $s->setDate_a($row['date_a']);
        $s->setDate_b($row['date_b']);
        $s->setPlace($row['place']);
        $s->setWoj($row['woj']);
        $s->setCena($row['cena']);
        $s->setCena_($row['cena_']);
        $s->setMail($row['mail']);
        $s->setPhone($row['phone']);
        $s->setContact($row['contact']);
        $s->setDesc($row['desc']);
        $s->setKategoria_name($row['kategoria_name']);
        $s->setObszar_name($row['obszar_name']);
        $s->setTematyka_name($row['tematyka_name']);
        $s->setTematyka($row['tematyka']);
        $s->setModuly_names($row['moduly_names']);
        $s->setKotm($row['kotm']);
        return $s;
    }
    
    /**
     * zwraca liczbe uslug w poszczegolnych kategoriach
     * 
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getServsSums(DBC $dbc) {

        $sql = Query::ServsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['kategoria_id']] = $r['ServsSums'];
        }
        return $Sums;
    }

    /**
     * zwraca liczbe uslug w poszczegolnych obszarach
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getSubservsSums(DBC $dbc) {
                $sql = Query::SubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['obszar_id']] = $r['SubservsSums'];
        }
        return $Sums;
        
    }

    /**
     *zwraca liczbe uslug w poszczegolnych tematykach
     * @param DBC $dbc
     * @return type
     * @throws DBQueryException 
     */
    public function getSubsubservsSums(DBC $dbc) {
                $sql = Query::SubsubservsSums();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            //throw new EmptyList();
            return array();
        $Sums = array();
        while ($r = $result->fetch_assoc()) {
            $Sums[$r['tematyka']] = $r['SubsubservsSums'];
        }
        return $Sums;
        
    }
}

?>
