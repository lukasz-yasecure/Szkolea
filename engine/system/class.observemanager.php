<?php

class ObserveManager
{
    public function addObserve(DBC $dbc, User $u, $par)
    {
        $sql = '';
        
        switch($par['what'])
        {
            case 'comms':
                $sql = Query::getObserveAddForCommKOT($u->getId_user(), $par['id']);
                break;
            case 'comm':
                $sql = Query::getObserveAddForComm($u->getId_user(), $par['id']);
                break;
            case 'servs':
                $sql = Query::getObserveAddForServKOT($u->getId_user(), $par['id']);
                break;
        }

        $res = $dbc->query($sql);
        if(!$res) return false;
        else return true;
    }
}

?>
