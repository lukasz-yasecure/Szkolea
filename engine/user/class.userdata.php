<?php

/**
 * operacje na danych od usera - formularze
 *
 *  2011-09-21  getRemindFromData() +
 *              getRemindMailData() +
 *              getPasswordChangeFormData() +
 *  2011-09-22  getRegisterFormData() +
 *  2011-09-24  getActivationFormData() +
 *  2011-09-28  getIdForLeftMenu
 *              getWhatForLeftMenu
 *  2011-10-03  NoRulesAccept, SomeErrors, NoMembersToJoin, TooManyMembers EXC
 *  2011-10-10  getLoginFormData()
 *              przy rejestracji uzywamy Password
 *  2011-10-11  BackURL
 *  2011-10-31  getCommision() walidowanie Long Days
 *  2011-11-09  getService()
 */
class UserData
{
    /**
     *
     * @return RemindFormData
     * @throws NoEmail jesli nie podano maila
     * @throws InvalidEmail jesli email jest bledny
     */
    public function getRemindFormData()
    {
        if(!isset($_POST['email'])) throw new NoEmail('');
        if(empty($_POST['email'])) throw new NoEmail('');
        if(!Valid::email($_POST['email'])) throw new InvalidEmail('');

        $rfd = new RemindFormData();
        $rfd->setEmail($_POST['email']);

        return $rfd;
    }

    /**
     *
     * @return ActivationMailData
     * @throws NoKey jesli nie ma jednego z kluczy
     */
    public function getActivationFormData()
    {
        if(!isset($_GET['k'])) throw new NoKey('');
        if(empty($_GET['k'])) throw new NoKey('');
        if(!isset($_GET['c'])) throw new NoKey('');
        if(empty($_GET['c'])) throw new NoKey('');

        $amd = new ActivationMailData();
        $amd->setMkey($_GET['k']);
        $amd->setCkey($_GET['c']);

        return $amd;
    }

    /**
     *
     * @return RemindMailData
     * @throws NoEmail jesli nie podano maila
     * @throws InvalidEmail jesli email jest bledny
     * @throws NoKey jesli nie ma klucza
     */
    public function getRemindMailData()
    {
        if(!isset($_GET['u'])) throw new NoEmail('');
        if(empty($_GET['u'])) throw new NoEmail('');
        if(!Valid::email($_GET['u'])) throw new InvalidEmail('');

        if(!isset($_GET['k'])) throw new NoKey('');
        if(empty($_GET['k'])) throw new NoKey('');


        $rmd = new RemindMailData();
        $rmd->setEmail($_GET['u']);
        $rmd->setKey($_GET['k']);

        return $rmd;
    }

    /**
     *
     * @return PasswordChangeFormData
     * @throws InsecurePassword
     * @throws PasswordsDontMatch
     */
    public function getPasswordChangeFormData()
    {
        if(!isset($_POST['pass1']) || !isset($_POST['pass2'])) throw new InsecurePassword('');
        if(empty($_POST['pass1']) || empty($_POST['pass2'])) throw new InsecurePassword('');
        if(!Valid::password($_POST['pass1'])) throw new InsecurePassword('');
        if($_POST['pass1'] !== $_POST['pass2']) throw new PasswordsDontMatch('');

        $pcfd = new PasswordChangeFormData();
        $pcfd->setPass(new Password($_POST['pass1']));

        return $pcfd;
    }


    /**
     *
     * @return RegisterFormData
     * @throws ErrorsInRegisterForm
     */
    public function getRegisterFormData()
    {
        //
        // REGULAMIN RODZAJ KONTA EMAIL HASLA
        //
        if(!isset($_POST['regulamin']) || $_POST['regulamin'] != 1) BFEC::add('wymagana jest akceptacja regulaminu!');

        if(!isset($_POST['ukind']) || empty($_POST['ukind']) || ($_POST['ukind'] != 'K' && $_POST['ukind'] != 'D')) BFEC::add('musisz okreslic rodzaj konta');
        else RFD::add('regForm', 'ukind', $_POST['ukind']);

        if(!isset($_POST['email']) || empty($_POST['email']) || !Valid::email($_POST['email'])) BFEC::add('musisz podac poprawny email');
        else RFD::add('regForm', 'email', $_POST['email']);

        if(!isset($_POST['pass1']) || !isset($_POST['pass2']) || empty($_POST['pass1']) || empty($_POST['pass2'])) BFEC::add('musisz dwukrotnie podac haslo');
        else
        {
            if(!Valid::password($_POST['pass1'])) BFEC::add('hasło nie spełnia norm');
            else
            {
                if($_POST['pass1'] !== $_POST['pass2']) BFEC::add('hasła nie pasuja');
            }
        }

        //
        // DANE OSOBOWE OBOWIAZKOWE
        //
        $os = 0;

        if(!isset($_POST['os_name']) || empty($_POST['os_name'])) $os++;
        else
        {
            if(Valid::name($_POST['os_name'])) RFD::add('regForm', 'os_name', $_POST['os_name']);
            else
            {
                BFEC::add('imie moze skladac sie tylko i wylacznie z liter');
                $os++;
            }
        }
        if(!isset($_POST['os_surname']) || empty($_POST['os_surname'])) $os++;
        else
        {
            if(Valid::surname($_POST['os_surname'])) RFD::add('regForm', 'os_surname', $_POST['os_surname']);
            else
            {
                BFEC::add('w nazwisku wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_street']) || empty($_POST['os_street'])) $os++;
        else
        {
            if(Valid::street($_POST['os_street'])) RFD::add('regForm', 'os_street', $_POST['os_street']);
            else
            {
                BFEC::add('w nazwie ulicy wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_house_number']) || empty($_POST['os_house_number'])) $os++;
        else
        {
            if(Valid::house_number($_POST['os_house_number'])) RFD::add('regForm', 'os_house_number', $_POST['os_house_number']);
            else
            {
                BFEC::add('w numerze domu/mieszkania wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_postcode']) || empty($_POST['os_postcode'])) $os++;
        else
        {
            if(Valid::postcode($_POST['os_postcode'])) RFD::add('regForm', 'os_postcode', $_POST['os_postcode']);
            else
            {
                BFEC::add('w kodzie pocztowym wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_city']) || empty($_POST['os_city'])) $os++;
        else
        {
            if(Valid::city($_POST['os_city'])) RFD::add('regForm', 'os_city', $_POST['os_city']);
            else
            {
                BFEC::add('w miescie wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_phone']) || empty($_POST['os_phone'])) $os++;
        else
        {
            if(Valid::phone($_POST['os_phone'])) RFD::add('regForm', 'os_phone', $_POST['os_phone']);
            else
            {
                BFEC::add('telefon w zlym formacie');
                $os++;
            }
        }

        if($os > 0 && $os < 7) BFEC::add('nalezy podac wszystkie wymagane dane osobowe');

        //
        // DANE FIRMOWE OBOWIAZKOWE
        //
        $f = 0;

        if(!isset($_POST['f_name']) || empty($_POST['f_name'])) $f++;
        else
        {
            if(Valid::name($_POST['f_name'])) RFD::add('regForm', 'f_name', $_POST['f_name']);
            else
            {
                BFEC::add('imie moze skladac sie tylko i wylacznie z liter');
                $f++;
            }
        }
        if(!isset($_POST['f_surname']) || empty($_POST['f_surname'])) $f++;
        else
        {
            if(Valid::surname($_POST['f_surname'])) RFD::add('regForm', 'f_surname', $_POST['f_surname']);
            else
            {
                BFEC::add('w nazwisku wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_position']) || empty($_POST['f_position'])) $f++;
        else
        {
            if(Valid::position($_POST['f_position'])) RFD::add('regForm', 'f_position', $_POST['f_position']);
            else
            {
                BFEC::add('stanowisko zawiera niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_company']) || empty($_POST['f_company'])) $f++;
        else
        {
            if(Valid::company($_POST['f_company'])) RFD::add('regForm', 'f_company', $_POST['f_company']);
            else
            {
                BFEC::add('w nazwie firmy wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_street']) || empty($_POST['f_street'])) $f++;
        else
        {
            if(Valid::street($_POST['f_street'])) RFD::add('regForm', 'f_street', $_POST['f_street']);
            else
            {
                BFEC::add('w nazwie ulicy wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_house_number']) || empty($_POST['f_house_number'])) $f++;
        else
        {
            if(Valid::house_number($_POST['f_house_number'])) RFD::add('regForm', 'f_house_number', $_POST['f_house_number']);
            else
            {
                BFEC::add('w numerze domu/mieszkania wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_postcode']) || empty($_POST['f_postcode'])) $f++;
        else
        {
            if(Valid::postcode($_POST['f_postcode'])) RFD::add('regForm', 'f_postcode', $_POST['f_postcode']);
            else
            {
                BFEC::add('w kodzie pocztowym wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_city']) || empty($_POST['f_city'])) $f++;
        else
        {
            if(Valid::city($_POST['f_city'])) RFD::add('regForm', 'f_city', $_POST['f_city']);
            else
            {
                BFEC::add('w miescie wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_phone']) || empty($_POST['f_phone'])) $f++;
        else
        {
            if(Valid::phone($_POST['f_phone'])) RFD::add('regForm', 'f_phone', $_POST['f_phone']);
            else
            {
                BFEC::add('telefon w zlym formacie');
                $f++;
            }
        }
        if(!isset($_POST['f_nip']) || empty($_POST['f_nip'])) $f++;
        else
        {
            if(Valid::nip($_POST['f_nip'])) RFD::add('regForm', 'f_nip', $_POST['f_nip']);
            else
            {
                BFEC::add('nip w zlym formacie');
                $f++;
            }
        }

        if($f > 0 && $f < 10) BFEC::add('nalezy podac wszystkie wymagane dane firmowe');

        if($os == 7 && $f == 10) BFEC::add('musisz uzupelnic wszystkie wymagane dane osobowe lub firmowe');

        //
        // DANE FIRMOWE I OSOBOWE NIEOBOWIAZKOWE
        //
        $rfd = new RegisterFormData();
        
        if(isset($_POST['f_regon']) && !empty($_POST['f_regon']))
        {
            if(Valid::regon($_POST['f_regon']))
            {
                RFD::add('regForm', 'f_regon', $_POST['f_regon']);
                $rfd->setF_regon($_POST['f_regon']);
            }
            else BFEC::add('regon w zlym formacie');
        }

        if(isset($_POST['f_krs']) && !empty($_POST['f_krs']))
        {
            if(Valid::krs($_POST['f_krs']))
            {
                RFD::add('regForm', 'f_krs', $_POST['f_krs']);
                $rfd->setF_krs($_POST['f_krs']);
            }
            else BFEC::add('krs w zlym formacie');
        }

        if(isset($_POST['os_woj']) && !empty($_POST['os_woj']))
        {
            if(Valid::woj($_POST['os_woj']))
            {
                RFD::add('regForm', 'os_woj', $_POST['os_woj']);
                $rfd->setOs_woj($_POST['os_woj']);
            }
            else BFEC::add('bledne wojewodztwo');
        }

        if(isset($_POST['f_woj']) && !empty($_POST['f_woj']))
        {
            if(Valid::woj($_POST['f_woj']))
            {
                RFD::add('regForm', 'f_woj', $_POST['f_woj']);
                $rfd->setF_woj($_POST['f_woj']);
            }
            else BFEC::add('bledne wojewodztwo');
        }

        if(BFEC::isError()) throw new ErrorsInRegisterForm('');

        //
        // RFD
        //

        $rfd->setEmail($_POST['email']);
        $rfd->setPass(new Password($_POST['pass1']));
        $rfd->setKind($_POST['ukind']);

        if($os == 0)
        {
            $rfd->setOs_name($_POST['os_name']);
            $rfd->setOs_surname($_POST['os_surname']);
            $rfd->setOs_street($_POST['os_street']);
            $rfd->setOs_house_number($_POST['os_house_number']);
            $rfd->setOs_postcode($_POST['os_postcode']);
            $rfd->setOs_city($_POST['os_city']);
            $rfd->setOs_phone($_POST['os_phone']);
        }

        if($f == 0)
        {
            $rfd->setF_name($_POST['f_name']);
            $rfd->setF_surname($_POST['f_surname']);
            $rfd->setF_position($_POST['f_position']);
            $rfd->setF_company($_POST['f_company']);
            $rfd->setF_street($_POST['f_street']);
            $rfd->setF_house_number($_POST['f_house_number']);
            $rfd->setF_postcode($_POST['f_postcode']);
            $rfd->setF_city($_POST['f_city']);
            $rfd->setF_phone($_POST['f_phone']);
            $rfd->setF_nip($_POST['f_nip']);
        }

        RFD::clear('regForm');

        return $rfd;
    }

    /**
     *
     * @return ProfileEditFormData
     * @throws ErrorsInRegisterForm
     */
    public function getProfileEditFormData()
    {
        //
        // DANE OSOBOWE OBOWIAZKOWE
        //
        $os = 0;

        if(!isset($_POST['os_name']) || empty($_POST['os_name'])) $os++;
        else
        {
            if(Valid::name($_POST['os_name'])) RFD::add('profEditForm', 'os_name', $_POST['os_name']);
            else
            {
                BFEC::add('imie moze skladac sie tylko i wylacznie z liter');
                $os++;
            }
        }
        if(!isset($_POST['os_surname']) || empty($_POST['os_surname'])) $os++;
        else
        {
            if(Valid::surname($_POST['os_surname'])) RFD::add('profEditForm', 'os_surname', $_POST['os_surname']);
            else
            {
                BFEC::add('w nazwisku wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_street']) || empty($_POST['os_street'])) $os++;
        else
        {
            if(Valid::street($_POST['os_street'])) RFD::add('profEditForm', 'os_street', $_POST['os_street']);
            else
            {
                BFEC::add('w nazwie ulicy wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_house_number']) || empty($_POST['os_house_number'])) $os++;
        else
        {
            if(Valid::house_number($_POST['os_house_number'])) RFD::add('profEditForm', 'os_house_number', $_POST['os_house_number']);
            else
            {
                BFEC::add('w numerze domu/mieszkania wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_postcode']) || empty($_POST['os_postcode'])) $os++;
        else
        {
            if(Valid::postcode($_POST['os_postcode'])) RFD::add('profEditForm', 'os_postcode', $_POST['os_postcode']);
            else
            {
                BFEC::add('w kodzie pocztowym wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_city']) || empty($_POST['os_city'])) $os++;
        else
        {
            if(Valid::city($_POST['os_city'])) RFD::add('profEditForm', 'os_city', $_POST['os_city']);
            else
            {
                BFEC::add('w miescie wystepuja niedozwolone znaki');
                $os++;
            }
        }
        if(!isset($_POST['os_phone']) || empty($_POST['os_phone'])) $os++;
        else
        {
            if(Valid::phone($_POST['os_phone'])) RFD::add('profEditForm', 'os_phone', $_POST['os_phone']);
            else
            {
                BFEC::add('telefon w zlym formacie');
                $os++;
            }
        }

        if($os > 0 && $os < 7) BFEC::add('nalezy podac wszystkie wymagane dane osobowe');

        //
        // DANE FIRMOWE OBOWIAZKOWE
        //
        $f = 0;

        if(!isset($_POST['f_name']) || empty($_POST['f_name'])) $f++;
        else
        {
            if(Valid::name($_POST['f_name'])) RFD::add('profEditForm', 'f_name', $_POST['f_name']);
            else
            {
                BFEC::add('imie moze skladac sie tylko i wylacznie z liter');
                $f++;
            }
        }
        if(!isset($_POST['f_surname']) || empty($_POST['f_surname'])) $f++;
        else
        {
            if(Valid::surname($_POST['f_surname'])) RFD::add('profEditForm', 'f_surname', $_POST['f_surname']);
            else
            {
                BFEC::add('w nazwisku wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_position']) || empty($_POST['f_position'])) $f++;
        else
        {
            if(Valid::position($_POST['f_position'])) RFD::add('profEditForm', 'f_position', $_POST['f_position']);
            else
            {
                BFEC::add('stanowisko zawiera niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_company']) || empty($_POST['f_company'])) $f++;
        else
        {
            if(Valid::company($_POST['f_company'])) RFD::add('profEditForm', 'f_company', $_POST['f_company']);
            else
            {
                BFEC::add('w nazwie firmy wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_street']) || empty($_POST['f_street'])) $f++;
        else
        {
            if(Valid::street($_POST['f_street'])) RFD::add('profEditForm', 'f_street', $_POST['f_street']);
            else
            {
                BFEC::add('w nazwie ulicy wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_house_number']) || empty($_POST['f_house_number'])) $f++;
        else
        {
            if(Valid::house_number($_POST['f_house_number'])) RFD::add('profEditForm', 'f_house_number', $_POST['f_house_number']);
            else
            {
                BFEC::add('w numerze domu/mieszkania wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_postcode']) || empty($_POST['f_postcode'])) $f++;
        else
        {
            if(Valid::postcode($_POST['f_postcode'])) RFD::add('profEditForm', 'f_postcode', $_POST['f_postcode']);
            else
            {
                BFEC::add('w kodzie pocztowym wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_city']) || empty($_POST['f_city'])) $f++;
        else
        {
            if(Valid::city($_POST['f_city'])) RFD::add('profEditForm', 'f_city', $_POST['f_city']);
            else
            {
                BFEC::add('w miescie wystepuja niedozwolone znaki');
                $f++;
            }
        }
        if(!isset($_POST['f_phone']) || empty($_POST['f_phone'])) $f++;
        else
        {
            if(Valid::phone($_POST['f_phone'])) RFD::add('profEditForm', 'f_phone', $_POST['f_phone']);
            else
            {
                BFEC::add('telefon w zlym formacie');
                $f++;
            }
        }
        if(!isset($_POST['f_nip']) || empty($_POST['f_nip'])) $f++;
        else
        {
            if(Valid::nip($_POST['f_nip'])) RFD::add('profEditForm', 'f_nip', $_POST['f_nip']);
            else
            {
                BFEC::add('nip w zlym formacie');
                $f++;
            }
        }

        if($f > 0 && $f < 10) BFEC::add('nalezy podac wszystkie wymagane dane firmowe');

        if($os == 7 && $f == 10) BFEC::add('musisz uzupelnic wszystkie wymagane dane osobowe lub firmowe');

        //
        // DANE FIRMOWE I OSOBOWE NIEOBOWIAZKOWE
        //
        $rfd = new ProfileEditFormData();
        
        if(isset($_POST['f_regon']) && !empty($_POST['f_regon']))
        {
            if(Valid::regon($_POST['f_regon']))
            {
                RFD::add('profEditForm', 'f_regon', $_POST['f_regon']);
                $rfd->setF_regon($_POST['f_regon']);
            }
            else BFEC::add('regon w zlym formacie');
        }

        if(isset($_POST['f_krs']) && !empty($_POST['f_krs']))
        {
            if(Valid::krs($_POST['f_krs']))
            {
                RFD::add('profEditForm', 'f_krs', $_POST['f_krs']);
                $rfd->setF_krs($_POST['f_krs']);
            }
            else BFEC::add('krs w zlym formacie');
        }

        if(isset($_POST['os_woj']) && !empty($_POST['os_woj']))
        {
            if(Valid::woj($_POST['os_woj']))
            {
                RFD::add('profEditForm', 'os_woj', $_POST['os_woj']);
                $rfd->setOs_woj($_POST['os_woj']);
            }
            else BFEC::add('bledne wojewodztwo');
        }

        if(isset($_POST['f_woj']) && !empty($_POST['f_woj']))
        {
            if(Valid::woj($_POST['f_woj']))
            {
                RFD::add('profEditForm', 'f_woj', $_POST['f_woj']);
                $rfd->setF_woj($_POST['f_woj']);
            }
            else BFEC::add('bledne wojewodztwo');
        }

        if(BFEC::isError()) throw new ErrorsInProfileEditForm('');

        //
        // RFD
        //

        if($os == 0)
        {
            $rfd->setOs_name($_POST['os_name']);
            $rfd->setOs_surname($_POST['os_surname']);
            $rfd->setOs_street($_POST['os_street']);
            $rfd->setOs_house_number($_POST['os_house_number']);
            $rfd->setOs_postcode($_POST['os_postcode']);
            $rfd->setOs_city($_POST['os_city']);
            $rfd->setOs_phone($_POST['os_phone']);
        }

        if($f == 0)
        {
            $rfd->setF_name($_POST['f_name']);
            $rfd->setF_surname($_POST['f_surname']);
            $rfd->setF_position($_POST['f_position']);
            $rfd->setF_company($_POST['f_company']);
            $rfd->setF_street($_POST['f_street']);
            $rfd->setF_house_number($_POST['f_house_number']);
            $rfd->setF_postcode($_POST['f_postcode']);
            $rfd->setF_city($_POST['f_city']);
            $rfd->setF_phone($_POST['f_phone']);
            $rfd->setF_nip($_POST['f_nip']);
        }

        RFD::clear('profEditForm');
        return $rfd;
    }

    /**
     *
     * @return string
     */
    public function getIdForLeftMenu()
    {
        $id = null;

        if(isset($_GET['id']) && !empty($_GET['id'])) $id = $_GET['id'];
        else if(isset($_GET['subsubcat']) && !empty($_GET['subsubcat'])) $id = $_GET['subsubcat'];
        else if(isset($_GET['subcat']) && !empty($_GET['subcat'])) $id = $_GET['subcat'];
        else if(isset($_GET['cat']) && !empty($_GET['cat'])) $id = $_GET['cat'];

        if(!is_null($id) && !Valid::csid($id)) $id = null; // sprawdzamy poprawnosc ID

        return $id;
    }

    /**
     *
     * @return string
     */
    public function getWhatForLeftMenu()
    {
        if(isset($_GET['what']) && $_GET['what'] == 'comms') return 'comms';
        else if(isset($_GET['what']) && $_GET['what'] == 'servs') return 'servs';
        else return 'comms';
    }

    /**
     *
     * NIEDOKONCZONE
     */
    public function getSearch()
    {
        $what = $this->getWhatForLeftMenu();

        $s = new Search();
        $s->setWhat($what);
        
        if(isset($_GET['id']) && !empty($_GET['id']) && Valid::csid($_GET['id']))
        {
            //RFD::add('searchForm', '', $_GET['']);
            $s->setKot_id($_GET['id']);
        }
        else if(isset($_GET['search']))
        {
            if(isset($_GET['cat']) && !empty($_GET['cat']) && Valid::kategoria($_GET['cat']))
            {
                RFD::add('searchForm', 'cat', $_GET['cat']);
                $s->setK($_GET['cat']);
            }

            if(isset($_GET['subcat']) && !empty($_GET['subcat']) && Valid::obszar($_GET['subcat']))
            {
                RFD::add('searchForm', 'subcat', $_GET['subcat']);
                $s->setO($_GET['subcat']);
            }

            if(isset($_GET['subsubcat']) && !empty($_GET['subsubcat']) && Valid::tematyka($_GET['subsubcat']))
            {
                RFD::add('searchForm', 'subsubcat', $_GET['subsubcat']);
                $s->setT($_GET['subsubcat']);
            }

            if(isset($_GET['date_a']) && !empty($_GET['date_a']) && Valid::add_comm_date($_GET['date_a']))
            {
                RFD::add('searchForm', 'date_a', $_GET['date_a']);
                $s->setData_a(UF::date2timestamp($_GET['date_a']));
            }

            if(isset($_GET['date_b']) && !empty($_GET['date_b']) && Valid::add_comm_date($_GET['date_b']))
            {
                RFD::add('searchForm', 'date_b', $_GET['date_b']);
                $s->setData_b(UF::date2timestamp($_GET['date_b']));
            }

            if(isset($_GET['place']) && !empty($_GET['place']) && Valid::city($_GET['place']))
            {
                RFD::add('searchForm', 'place', $_GET['place']);
                $s->setPlace($_GET['place']);
            }

            if(isset($_GET['woj']) && !empty($_GET['woj']) && Valid::woj($_GET['woj']))
            {
                RFD::add('searchForm', 'woj', $_GET['woj']);
                $s->setWoj($_GET['woj']);
            }

            if(isset($_GET['cena_min']) && !empty($_GET['cena_min']) && Valid::price($_GET['cena_min']))
            {
                RFD::add('searchForm', 'cena_min', $_GET['cena_min']);
                $s->setCena_min($_GET['cena_min']);
            }

            if(isset($_GET['cena_max']) && !empty($_GET['cena_max']) && Valid::price($_GET['cena_max']))
            {
                RFD::add('searchForm', 'cena_max', $_GET['cena_max']);
                $s->setCena_max($_GET['cena_max']);
            }

            if(isset($_GET['what']) && ($_GET['what'] == 'comms' || $_GET['what'] == 'servs'))
            {
                RFD::add('searchForm', 'what', $_GET['what']);
                $s->setWhat($_GET['what']);
            }

            if(isset($_GET['word']) && !empty($_GET['word']) && Valid::text($_GET['word']))
            {
                RFD::add('searchForm', 'word', $_GET['word']);
                $s->setWord($_GET['word']);
            }
        }
        else
        {
            $s->setAll(true);
        }

        return $s;
    }

    public function getCommGroupJoinFormData(Commision $c)
    {
        if(!isset($_POST['reg']) || $_POST['reg'] != '1') throw new NoRulesAccept();

        $parts = '';
        $parts_count = 0;
        $errors = false;

        if(isset($_POST['part0']) && $_POST['part0'] == 1)
        {
            $parts.= '; zapisujacy';
            $parts_count++;
        }

        for($i=2; $i<=16; $i++)
        {
            if(isset($_POST['part'.$i.'_name']) && isset($_POST['part'.$i.'_surname']) && !empty($_POST['part'.$i.'_name']) && !empty($_POST['part'.$i.'_surname']))
            {
                if(Valid::name($_POST['part'.$i.'_name']) && Valid::surname($_POST['part'.$i.'_surname']))
                {
                    $parts.= '; '.$_POST['part'.$i.'_name'].' '.$_POST['part'.$i.'_surname'];
                    $parts_count++;
                }
                else
                {
                    BFEC::add($_POST['part'.$i.'_name'].' '.$_POST['part'.$i.'_surname'].' - imie lub nazwisko zawiera niewlasciwe znaki!');
                    $errors = true;
                }
            }
        }

        if($errors) throw new SomeErrors();
        if($parts_count == 0) throw new NoMembersToJoin();
        if($parts_count+$c->getParts_count() > 16) throw new TooManyMembers();

        // trzeba zwiekszych part_count przy Comm
        // trzeba dopisac zapisanych w tabeli
        $sql1 = 'UPDATE `szkolea`.`commisions` SET `parts_count` = \''.($parts_count+$c->getParts_count()).'\' WHERE `commisions`.`id_comm` ='.$c->getId_comm();
        $sql2 = 'INSERT INTO `commisions_group` (`id_comm`, `id_user`, `date_add`) VALUES ';

        $z = $parts_count;

        while(($z--) > 0)
        {
            $sql2.= '(\''.$c->getId_comm().'\', \'69\', \''.time().'\'), ';
        }

        $sql2 = substr($sql2, 0, -2);

        $db = new DBC(new System('comm_group_join', true));
        $db->query($sql1);
        $db->query($sql2);
    }

    /**
     *
     * @return LoginFormData
     * @throws SomeErrors
     */
    public function getLoginFormData()
    {
        $e = false;

        if(!isset($_POST['email']) || empty($_POST['email']))
        {
            $e = true;
            BFEC::add(BFEC::$e['UD']['NoEmail']);
        }
        else if(!Valid::email($_POST['email']))
        {
            $e = true;
            BFEC::add(BFEC::$e['UD']['NoValidEmail']);
        }
        else RFD::add('logForm', 'email', $_POST['email']);

        if(!isset($_POST['pass']) || empty($_POST['pass']))
        {
            $e = true;
            BFEC::add(BFEC::$e['UD']['NoPass']);
        }
        else if(!Valid::password($_POST['pass']))
        {
            $e = true;
            BFEC::add(BFEC::$e['UD']['NoValidPass']);
        }

        if($e) throw new SomeErrors();

        $lfd = new LoginFormData();
        $lfd->setEmail($_POST['email']);
        $lfd->setPass(new Password($_POST['pass']));

        return $lfd;
    }

    /**
     *
     * @return Commision 
     */
    public function getCommision()
    {
        //
        // REGULAMIN
        //
        if(!isset($_POST['regulamin']) || $_POST['regulamin'] != 1) BFEC::add(BFEC::$e['UD']['regulamin']);

        //
        // POLA OBOWIAZKOWE
        //
        if(!isset($_POST['cat']) || empty($_POST['cat']) || !Valid::kategoria($_POST['cat'])) BFEC::add(BFEC::$e['UD']['kategoria']);
        else RFD::add('addCommForm', 'cat', $_POST['cat']);

        /*STD::dpr(isset($_POST['subcat']));
        STD::dpr(empty($_POST['subcat']));
        STD::dpr(Valid::obszar($_POST['subcat']));
        exit();*/

        if(!isset($_POST['subcat']) || empty($_POST['subcat']) || !Valid::obszar($_POST['subcat'])) BFEC::add(BFEC::$e['UD']['obszar']);
        else RFD::add('addCommForm', 'subcat', $_POST['subcat']);

        if(!isset($_POST['subsubcat']) || empty($_POST['subsubcat']) || !Valid::tematyka($_POST['subsubcat'])) BFEC::add(BFEC::$e['UD']['tematyka']);
        else RFD::add('addCommForm', 'subsubcat', $_POST['subsubcat']);

        if(!isset($_POST['moduly']) || empty($_POST['moduly']) || !is_array($_POST['moduly']) || count($_POST['moduly']) <= 0) BFEC::add(BFEC::$e['UD']['moduly']);
        else
        {
            $valid = true;
            
            foreach($_POST['moduly'] as $m)
            {
                if(!Valid::modul($m))
                {
                    $valid = false;
                    break;
                }
            }

            if($valid) RFD::add('addCommForm', 'moduly', $_POST['moduly']);
            else BFEC::add(BFEC::$e['UD']['moduly']);
        }

        if(!isset($_POST['long']) || empty($_POST['long']) || !Valid::add_comm_long($_POST['long'])) BFEC::add(BFEC::$e['UD']['long']);
        else RFD::add('addCommForm', 'long', $_POST['long']);

        if(!isset($_POST['days']) || empty($_POST['days']) || !is_array($_POST['days']) || !Valid::add_comm_days($_POST['days'])) BFEC::add(BFEC::$e['UD']['days']);
        else
        {
            if(!Valid::add_comm_days_continuity($_POST['days'], RFD::get('addCommForm', 'long'))) BFEC::add(BFEC::$e['UD']['days_continuity']);
            else RFD::add('addCommForm', 'days', $_POST['days']);
        }

        if(!isset($_POST['date_a']) || empty($_POST['date_a']) || !Valid::add_comm_date($_POST['date_a'])) BFEC::add(BFEC::$e['UD']['date']);
        else RFD::add('addCommForm', 'date_a', $_POST['date_a']);

        if(!isset($_POST['date_b']) || empty($_POST['date_b']) || !Valid::add_comm_date($_POST['date_b'])) BFEC::add(BFEC::$e['UD']['date']);
        else RFD::add('addCommForm', 'date_b', $_POST['date_b']);

        if(!Valid::add_comm_date_long(UF::date2timestamp(RFD::get('addCommForm', 'date_a')), UF::date2timestamp(RFD::get('addCommForm', 'date_b')), RFD::get('addCommForm', 'long'))) BFEC::add(BFEC::$e['UD']['date_long']);

        if(isset($_POST['drugi_termin']))
        {
            if(!isset($_POST['date_c']) || empty($_POST['date_c']) || !Valid::add_comm_date($_POST['date_c'])) BFEC::add(BFEC::$e['UD']['date']);
            else RFD::add('addCommForm', 'date_c', $_POST['date_c']);

            if(!isset($_POST['date_d']) || empty($_POST['date_d']) || !Valid::add_comm_date($_POST['date_d'])) BFEC::add(BFEC::$e['UD']['date']);
            else RFD::add('addCommForm', 'date_d', $_POST['date_d']);

            if(!Valid::add_comm_date_long(UF::date2timestamp(RFD::get('addCommForm', 'date_c')), UF::date2timestamp(RFD::get('addCommForm', 'date_d')), RFD::get('addCommForm', 'long'))) BFEC::add(BFEC::$e['UD']['date_long']);
        }

        if(!isset($_POST['expire']) || empty($_POST['expire']) || !Valid::waznosc($_POST['expire'])) BFEC::add(BFEC::$e['UD']['expire']);
        else RFD::add('addCommForm', 'expire', $_POST['expire']);

        if(!isset($_POST['place']) || empty($_POST['place']) || !Valid::city($_POST['place'])) BFEC::add(BFEC::$e['UD']['place']);
        else RFD::add('addCommForm', 'place', $_POST['place']);

        if(isset($_POST['woj']) && !empty($_POST['woj']) && Valid::woj($_POST['woj'])) RFD::add('addCommForm', 'woj', $_POST['woj']);

        $cena1 = $cena2 = false;

        if(!isset($_POST['cena_min']) || empty($_POST['cena_min']) || !Valid::price($_POST['cena_min'])) BFEC::add(BFEC::$e['UD']['cena_min']);
        else $cena1 = true;

        if(!isset($_POST['cena_max']) || empty($_POST['cena_max']) || !Valid::price($_POST['cena_max'])) BFEC::add(BFEC::$e['UD']['cena_max']);
        else $cena2 = true;

        if($cena1 && $cena2 && Valid::add_comm_ceny($_POST['cena_min'], $_POST['cena_max']))
        {
            RFD::add('addCommForm', 'cena_min', $_POST['cena_min']);
            RFD::add('addCommForm', 'cena_max', $_POST['cena_max']);
        }
        else BFEC::add(BFEC::$e['UD']['ceny']);

        /*
         * TRZEBA ZLICZYC UCZESTNIKOW - MUSI BYC CO NAJMNIEJ 1
         */
        $participants = 0;
        $parts = array();

        if(isset($_POST['part0']) && !empty($_POST['part0']))
        {
            $parts[] = array('name' => 'autor', 'surname' => 'autor');
            RFD::add('addCommForm', 'part0', $_POST['part0']);
            $participants++;
        }

        for($i=2; $i<=16; $i++)
        {
            if(isset($_POST['part'.$i.'_name']) && !empty($_POST['part'.$i.'_name']) && isset($_POST['part'.$i.'_surname']) && !empty($_POST['part'.$i.'_surname']))
            {
                if(Valid::name($_POST['part'.$i.'_name']) && Valid::name($_POST['part'.$i.'_surname']))
                {
                    $parts[] = array('name' => $_POST['part'.$i.'_name'], 'surname' => $_POST['part'.$i.'_surname']);
                    $participants++;
                }
                else BFEC::add(BFEC::$e['UD']['uczestnik']);
            }
        }

        if($participants < 1) BFEC::add(BFEC::$e['UD']['liczba_ucz']);
        if(count($parts) > 0) RFD::add('addCommForm', 'parts', $parts);

        /**
         * wszystko ZAJEBISCIE teraz uzueplnic obiekt i wrzucic do bazy! :)
         */

        /*
         * if(!isset($_POST['x']) || empty($_POST['x']) || !Valid::kategoria($_POST['x'])) BFEC::add('');
        else RFD::add('addCommForm', 'cats', $_POST['x']);
         */

        $c = new Commision();

        if(BFEC::isError()) throw new ErrorsInAddCommForm();

        $c->setCat($_POST['cat']);
        $c->setSubcat($_POST['subcat']);
        $c->setSubsubcat($_POST['subsubcat']);
        $c->setModuly($_POST['moduly']);
        $c->setLong($_POST['long']);
        $c->setDays($_POST['days']);
        $c->setDate_a($_POST['date_a']);
        $c->setDate_b($_POST['date_b']);
        if(isset($_POST['drugi_termin']))
        {
            if(isset($_POST['date_c'])) $c->setDate_c($_POST['date_c']);
            if(isset($_POST['date_d'])) $c->setDate_d($_POST['date_d']);
        }
        $c->setExpire($_POST['expire']);
        $c->setPlace($_POST['place']);
        if(isset($_POST['woj'])) $c->setWoj($_POST['woj']);
        $c->setCena_min($_POST['cena_min']);
        $c->setCena_max($_POST['cena_max']);
        $c->setParts($parts);
        $c->setDate_add(time());
        $c->setDate_end(time()+(24*3600*$_POST['expire']));
        $c->setParts_count($participants);
        RFD::clear('addCommForm');

        return $c;
    }

    public function getService()
    {
        //
        // REGULAMIN
        //
        if(!isset($_POST['regulamin']) || $_POST['regulamin'] != 1) BFEC::add(BFEC::$e['UD']['regulamin']);

        //
        // POLA OBOWIAZKOWE
        //
        if(!isset($_POST['name']) || empty($_POST['name']) || !Valid::serv_name($_POST['name'])) BFEC::add(BFEC::$e['UD']['name']);
        else RFD::add('addServForm', 'name', $_POST['name']);
        
        if(!isset($_POST['cat']) || empty($_POST['cat']) || !Valid::kategoria($_POST['cat'])) BFEC::add(BFEC::$e['UD']['kategoria']);
        else RFD::add('addServForm', 'cat', $_POST['cat']);

        /*STD::dpr(isset($_POST['subcat']));
        STD::dpr(empty($_POST['subcat']));
        STD::dpr(Valid::obszar($_POST['subcat']));
        exit();*/

        if(!isset($_POST['subcat']) || empty($_POST['subcat']) || !Valid::obszar($_POST['subcat'])) BFEC::add(BFEC::$e['UD']['obszar']);
        else RFD::add('addServForm', 'subcat', $_POST['subcat']);

        if(!isset($_POST['subsubcat']) || empty($_POST['subsubcat']) || !Valid::tematyka($_POST['subsubcat'])) BFEC::add(BFEC::$e['UD']['tematyka']);
        else RFD::add('addServForm', 'subsubcat', $_POST['subsubcat']);

        $m = true;
        $p = true;

        if(!isset($_POST['moduly']) || empty($_POST['moduly']) || !is_array($_POST['moduly']) || count($_POST['moduly']) <= 0) $m = false;
        else
        {
            $valid = true;

            foreach($_POST['moduly'] as $m)
            {
                if(!Valid::modul($m))
                {
                    $valid = false;
                    break;
                }
            }

            if($valid) RFD::add('addServForm', 'moduly', $_POST['moduly']);
            else $m = false;
        }

        if(!isset($_POST['program']) || empty($_POST['program']) || !Valid::text($_POST['program'])) $p = false;
        else RFD::add('addServForm', 'program', $_POST['program']);

        if(!$m && !$p) BFEC::add(BFEC::$e['UD']['brak_programu']);

        if(!isset($_POST['date_uzg']))
        {
            if(!isset($_POST['date_a']) || empty($_POST['date_a']) || !Valid::add_comm_date($_POST['date_a'])) BFEC::add(BFEC::$e['UD']['date']);
            else RFD::add('addServForm', 'date_a', $_POST['date_a']);

            if(!isset($_POST['date_b']) || empty($_POST['date_b']) || !Valid::add_comm_date($_POST['date_b'])) BFEC::add(BFEC::$e['UD']['date']);
            else RFD::add('addServForm', 'date_b', $_POST['date_b']);
        }
        else RFD::add('addServForm', 'date_uzg', true);

        if(!isset($_POST['place']) || empty($_POST['place']) || !Valid::city($_POST['place'])) BFEC::add(BFEC::$e['UD']['place']);
        else RFD::add('addServForm', 'place', $_POST['place']);

        if(isset($_POST['woj']) && !empty($_POST['woj']) && Valid::woj($_POST['woj'])) RFD::add('addServForm', 'woj', $_POST['woj']);

        if(!isset($_POST['cena']) || empty($_POST['cena']) || !Valid::price($_POST['cena'])) BFEC::add(BFEC::$e['UD']['cena']);
        else RFD::add('addServForm', 'cena', $_POST['cena']);

        if(!isset($_POST['cena_']) || empty($_POST['cena_']) || !Valid::add_serv_cena_($_POST['cena_'])) BFEC::add(BFEC::$e['UD']['cena_']);
        else RFD::add('addServForm', 'cena_', $_POST['cena_']);

        if(isset($_POST['desc']) && !empty($_POST['desc']) && Valid::text($_POST['desc'])) RFD::add('addServForm', 'desc', $_POST['desc']);

        if(!isset($_POST['mail']) || empty($_POST['mail']) || !Valid::email($_POST['mail'])) BFEC::add(BFEC::$e['UD']['NoValidEmail']);
        else RFD::add('addServForm', 'mail', $_POST['mail']);

        if(!isset($_POST['phone']) || empty($_POST['phone']) || !Valid::phone($_POST['phone'])) BFEC::add(BFEC::$e['UD']['phone']);
        else RFD::add('addServForm', 'phone', $_POST['phone']);

        if(!isset($_POST['contact']) || empty($_POST['contact']) || !Valid::contact($_POST['contact'])) BFEC::add(BFEC::$e['UD']['contact']);
        else RFD::add('addServForm', 'contact', $_POST['contact']);

        /**
         * wszystko ZAJEBISCIE teraz uzueplnic obiekt i wrzucic do bazy! :)
         */

        /*
         * if(!isset($_POST['x']) || empty($_POST['x']) || !Valid::kategoria($_POST['x'])) BFEC::add('');
        else RFD::add('addCommForm', 'cats', $_POST['x']);
         */

        $s = new Service();

        if(BFEC::isError()) throw new ErrorsInAddServForm();

        $s->setName($_POST['name']);
        $s->setCat($_POST['cat']);
        $s->setSubcat($_POST['subcat']);
        $s->setSubsubcat($_POST['subsubcat']);
        if(isset($_POST['moduly'])) $s->setModuly($_POST['moduly']);
        if(isset($_POST['program'])) $s->setProgram($_POST['program']);
        if(isset($_POST['date_uzg'])) $s->setDate_uzg(true);
        if(isset($_POST['date_a'])) $s->setDate_a($_POST['date_a']);
        if(isset($_POST['date_b'])) $s->setDate_b($_POST['date_b']);
        $s->setPlace($_POST['place']);
        if(isset($_POST['woj'])) $s->setWoj($_POST['woj']);
        $s->setCena($_POST['cena']);
        $s->setCena_($_POST['cena_']);
        $s->setMail($_POST['mail']);
        $s->setPhone($_POST['phone']);
        $s->setContact($_POST['contact']);
        if(isset($_POST['desc'])) $s->setDesc($_POST['desc']);
        $s->setDate_add(time());
        $s->setDate_end(time()+(24*3600*10)); // to pewnie do zmiany, ustawilem na stale 10 dni
        RFD::clear('addServForm');

        return $s;
    }

    /**
     * Pobiera z URL-a ID i sprawdza jego poprawnosc
     *
     * @return int
     */
    public function getIDFromURL()
    {
        if(!is_numeric($_GET['id'])) throw new InvalidID('Próba wywołania zlecenia z niepoprawnym ID: '.$_GET['id']);
        $id = intval($_GET['id']);
        if($id <= 0) throw new InvalidID('Próba wywołania zlecenia z niepoprawnym ID: '.$_GET['id']);

        return $id;
    }

    public function getObserveParamsFromURL()
    {
        if(isset($_GET['id']) && isset($_GET['what']))
        {
            if(!Valid::id($_GET['id'])) return false;
            
            if($_GET['what'] == 'comms' || $_GET['what'] == 'servs' || $_GET['what'] == 'comm')
            {
                return array('what' => $_GET['what'], 'id' => $_GET['id']);
            }
            else return false;
        }
        else return false;
    }

    public function getCommJoinAndOfferData()
    {
        if(!isset($_GET['reg']) || $_GET['reg'] != '1') throw new NoRulesAccept();
        if(Valid::isNatural($_GET['id']))
        {
            return $_GET['id'];
        }
        else throw new InvalidID('Próba wywołania zlecenia z niepoprawnym ID: '.$_GET['id']);
    }

    /**
     *
     * @return Offer 
     */
    public function getCommOffer()
    {
        if(!isset($_GET['id']) || !Valid::isNatural($_GET['id'])) BFEC::add('Niepoprawny ID!');

        if(isset($_GET['cena']) && Valid::price($_GET['cena'])) RFD::add('CO', 'cena', $_GET['cena']);
        else BFEC::add(BFEC::$e['UD']['cena']);

        if(!isset($_GET['cenax']) || $_GET['cenax'] < 1 || $_GET['cenax'] > 3) BFEC::add(BFEC::$e['UD']['cena_']);
        else RFD::add('CO', 'cenax', $_GET['cenax']);

        if(!isset($_GET['rozl']) || $_GET['rozl'] < 1 || $_GET['rozl'] > 3) BFEC::add('Musisz podać sposób rozliczania!');
        else RFD::add('CO', 'rozl', $_GET['rozl']);

        if(isset($_GET['inne']) && is_array($_GET['inne']))
        {
            $inne = implode(';', $_GET['inne']);
            RFD::add('CO', 'inne', $inne);

            if($inne[strlen($inne)-1] == '4')
            {
                if(isset($_GET['ile_kaw']) && Valid::isNatural($_GET['ile_kaw'])) RFD::add('CO', 'ile_kaw', $_GET['ile_kaw']);
                else BFEC::add('Musisz podać ilość przerw kawowych!');
            }
        }

        if(isset($_GET['date_a']) && ($dateA = Valid::date2timestamp($_GET['date_a'])) !== false) RFD::add('CO', 'date_a', $_GET['date_a']);
        else BFEC::add(BFEC::$e['UD']['date']);

        if(isset($_GET['date_b']) && ($dateB = Valid::date2timestamp($_GET['date_b'])) !== false) RFD::add('CO', 'date_b', $_GET['date_b']);
        else BFEC::add(BFEC::$e['UD']['date']);

        if(BFEC::isError()) throw new SomeErrors();

        $o = new Offer();
        $o->setId_comm($_GET['id']);
        $o->setCena($_GET['cena']);
        $o->setCenax($_GET['cenax']);
        $o->setRozl($_GET['rozl']);
        if(isset($_GET['inne']) && !empty($_GET['inne'])) $o->setInne($_GET['inne']);
        if(isset($_GET['ile_kaw']) && !empty($_GET['ile_kaw'])) $o->setIle_kaw($_GET['ile_kaw']);
        $o->setDate_a($_GET['date_a']);
        $o->setDate_b($_GET['date_b']);
        RFD::clear('CO');
        return $o;
    }
}

?>
