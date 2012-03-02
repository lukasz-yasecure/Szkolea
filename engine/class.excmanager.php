<?php

class EXCManager {

    private $fatal = 'Serwis Szkolea.pl jest obecnie poddawany pracom konserwacyjnym! Nie potrwa to długo, więc prosimy o odrobinę cierpliwości. Przepraszamy za kłopot i zapraszamy później!';

    public function __construct(Exception $e, $s = null) {
        $eName = get_class($e);

        switch ($eName) {
            case 'MailDidNotSend':
                Log::MailDidNotSend($e);
                if ($s == 'register')
                    BFEC::add(MSG::activationMailFail(), true, Pathes::getScriptIndexPath());
                else
                    BFEC::add(MSG::sendMailFail(), true, Pathes::getScriptIndexPath());
                break;
            case 'ModuleDoesNotExist':
            case 'BasicModuleDoesNotExist':
            case 'NoDefinitionForAction':
                Log::System($e);
                $this->fatal();
                break;

            case 'UserIsNotLogged':
                BFEC::add(BFEC::$e['PM']['UserIsNotLogged'], true, Pathes::getScriptLoginPath());
                break;

            case 'UserIsLogged':
                BFEC::add(BFEC::$e['PM']['UserIsLogged'], true, Pathes::getScriptIndexPath());
                break;

            case 'UserIsNotDostawca':
                if ($s == 'comm')
                    BFEC::add(BFEC::$e['PM']['UserIsNotDostawca_offer'], true, Pathes::getScriptIndexPath());
                else
                    BFEC::add(BFEC::$e['PM']['UserIsNotDostawca'], true, Pathes::getScriptIndexPath());
                break;

            case 'UserIsNotKlient':
                if ($s == 'comm')
                    BFEC::add(BFEC::$e['PM']['UserIsNotKlient_join'], true, Pathes::getScriptIndexPath());
                else
                    BFEC::add(BFEC::$e['PM']['UserIsNotKlient'], true, Pathes::getScriptIndexPath());
                break;

            case 'UserIsNotActivated':
                BFEC::add(BFEC::$e['UM']['nieaktywowany'], true, Pathes::getScriptIndexPath());
                break;

            case 'NoTemplateFile':
                Log::NoTemplateFile($e);
                $this->fatal();
                break;

            case 'ErrorsInAddServForm':
                BFEC::add('', true, Pathes::getScriptAddServPath());
                break;

            case 'ErrorsInRegisterForm':
                BFEC::add('', true, Pathes::getScriptRegisterPath());
                break;

            case 'EmailIsNotAvailable':
                BFEC::add(MSG::registerErrorEmailIsNotAvailable(), true, Pathes::getScriptRegisterPath());
                break;

            case 'DBConnectException':
                Log::DBConnect($e);
                $this->fatal();
                break;

            case 'DBCharsetException':
                Log::DBCharset($e);
                $this->fatal();
                break;

            case 'DBQueryException':
                Log::DBQuery($e);
                $this->fatal();
                break;

            case 'InvalidID':
                Log::HackingAttempt($e);
                BFEC::add('', true, Pathes::getScriptIndexPath());
                break;

            case 'NoRulesAccept':
                BFEC::add(BFEC::$e['UD']['regulamin'], true, SessionManager::getBackURL_Static());
                break;

            case 'SomeErrors':
                BFEC::add('', true, SessionManager::getBackURL_Static());
                break;

            case 'UMNoUser':
                Log::NoUser($e);
                $this->fatal();
                break;

            case 'UMTooManyUsers':
                Log::TooManyUsers($e);
                $this->fatal();
                break;

            case 'NoPreparedActivationKeys':
                Log::NoPreparedActivationKeys($e);
                $this->fatal();
                break;

            case 'NieprawidloweIdPakietu':
                BFEC::add(MSG::profilePackageIdError(), true, Pathes::getScriptProfilePackageBuyingPath());
                break;

            case 'NieDodanoPakietu':
                if($s == 'register')
                    BFEC::add(MSG::profilePackageAddErrorInRegister(), true, Pathes::getScriptIndexPath());
                else
                    BFEC::add(MSG::profilePackageAddError(), true, Pathes::getScriptProfilePackagesPath());
                break;

            case 'BrakAktywnychPakietow':
                BFEC::add(MSG::profileNoPackages(), true, Pathes::getScriptProfilePath());
                break;
            
            case 'NieMoznaDodawacUslug':
                BFEC::add(MSG::profileNoServicesAllow(), true, Pathes::getScriptProfilePackageBuyingPath());
                break;
            
                        case 'NieMoznaDodawacOfert':
                BFEC::add(MSG::profileNoOffersAllow(), true, Pathes::getScriptProfilePackageBuyingPath());
                break;
            
              case 'NieZaktualizowanoWizytowki':
                BFEC::add(MSG::profileNoCardUpdate(), true, Pathes::getScriptProfileCard());
                break;

            default:
                exit($eName);
                break;
        }
    }

    private function fatal() {
        exit($this->fatal);
    }

}

?>
