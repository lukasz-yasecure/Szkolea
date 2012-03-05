<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if (file_exists($sysFile) && file_exists($logFile)) {
    require_once($sysFile);
    require_once($logFile);
} else {
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir . date('Ymd') . '_system_exc.log', date('Y-m-d H:i:s') . ' SYSTEM/LOG NIEDOSTEPNY!' . PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/* * *********************[ action = XXX ]****************************************************************************
 *
 * 2012-03-02
 * obsługa uploadera loga dla wizytówki
 *
 * ********************************************************************************************************************* */

try {

    $sys = new System('image_uploader', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $p = $pm->checkPrivileges($u);
    $pkgm = new PackageManager();
    $dbc = new DBC($sys);



    $path = "loga/";


    $valid_formats = array("jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF");   //dozwolone formaty
    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
        $name = $_FILES['photoimg']['name'];    //nazwa pliku
        $size = $_FILES['photoimg']['size'];    //rozmiar pliku


        if (strlen($name)) {
            list($txt, $ext) = explode(".", $name);
            if (in_array($ext, $valid_formats)) {
                if ($size < (100 * 1024)) { //rozmiar pliku w KB = (ROZMIAR W BAJTACH)*1024
                    $actual_image_name = $u->getId_user() . "_" . time() . "." . $ext;
                    $tmp = $_FILES['photoimg']['tmp_name'];


                    if (move_uploaded_file($tmp, $path . $actual_image_name)) {

                        $image_size = getimagesize('loga/' . $actual_image_name);

                        if ($image_size[1] > 200 || $image_size[2] > 300) {
                            unlink('loga/' . $actual_image_name);
                            echo 'Logo jest za duże! Dozwolony rozmiar maksymalny to 300x200 px';
                        } else {

                            //dodawanie do bazy
                            if ($pkgm->sprawdzWizytowke($dbc, $u->getId_user()) == FALSE) { //jeśli wizytówka jest nową pozycją w bazie
                                $pkgm->pobierzWizytowke($dbc, $u->getId_user());
                                //usunięcie starego logo
                                if (strlen($pkgm->pobierzLogoLink()) > 0 && !($pkgm->pobierzLogoLink() == 'NULL'))
                                    unlink('loga/' . $pkgm->pobierzLogoLink());
                                $sql = Query::setNewCardForUser($u->getId_user(), 'NULL', 'NULL', $actual_image_name);
                                $dbc->query($sql);
                                if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                    echo 'Nie dodano logo!';
                            }else {     //jeśli wizytówka już istnieje w bazie
                                $pkgm->pobierzWizytowke($dbc, $u->getId_user());
                                //usunięcie starego logo
                                if (strlen($pkgm->pobierzLogoLink()) > 0 && !($pkgm->pobierzLogoLink() == 'NULL'))
                                    unlink('loga/' . $pkgm->pobierzLogoLink());
                                $sql = Query::setLogoForUser($u->getId_user(), $actual_image_name);
                                $dbc->query($sql);
                                if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                    echo 'Nie dodano logo!';
                            }

                            echo "<img src='loga/" . $actual_image_name . "'  class='preview' border=2>";   //wyświetlenie loga po dodaniu
                        }
                    }
                    else
                        echo "Niepowodzenie";
                }
                else
                    echo "Maksymalny rozmiar pliku to 100kB";
            }
            else
                echo "Niewłaści format pliku. Obsługiwane formaty: jpg, jpeg, png, gif";
        }

        else
            echo "Proszę wybrać logo.";

        exit;
    }
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>