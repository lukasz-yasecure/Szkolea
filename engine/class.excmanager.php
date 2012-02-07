<?php

class EXCManager {

    private $fatal = 'Fatal error.';

    public function __construct(Exception $e, $s = null) {
        $eName = get_class($e);

        switch ($eName) {
            case 'MailDidNotSend':
                Log::MailDidNotSend($e);
                BFEC::add('Nie udało się wysłać maila! Spróbuj ponownie później.', true, Pathes::getScriptIndexPath());
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

            default:
                exit('zdefiniuj ten blad: ' . $eName);
                break;
        }
    }

    private function fatal() {
        exit($this->fatal);
    }

}

?>
