<?php

//
// System
//

class ModuleDoesNotExist extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

class BasicModuleDoesNotExist extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

class NoDefinitionForAction extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

//
// PrivilegesManager
//

class UserIsLogged extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsNotLogged extends Exception // jest w EXCM
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsNotDostawca extends Exception // jest w EXCM
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsNotKlient extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsNotAdmin extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsActivated extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class UserIsNotActivated extends Exception // jest w EXCM
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

//
// TemplateManager
//

class NoTemplateFile extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

//
// DBC
//

class DBConnectException extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

class DBQueryException extends Exception
{
  public function __construct($error, $sql, $errno = 0){
    parent::__construct($error.' SQL Query: '.$sql, $errno);
  }
}

class DBCharsetException extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

//
// UserData
//

class NoRulesAccept extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class SomeErrors extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class NoMembersToJoin extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class TooManyMembers extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class NoEmail extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class InvalidEmail extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class NoKey extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class NoPassword extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class InsecurePassword extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class PasswordsDontMatch extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class ErrorsInRegisterForm extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class ErrorsInProfileEditForm extends Exception
{
  public function __construct($error){
    parent::__construct($error, 0);
  }
}

class ErrorsInAddCommForm extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class ErrorsInAddServForm extends Exception
{
  public function __construct(){
    parent::__construct('', 0);
  }
}

class InvalidID extends Exception
{
  public function __construct($msg){
    parent::__construct($msg, 0);
  }
}

//
// PackageManager
//

class BrakAktywnychPakietow extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaDodawacOfert extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaDodawacUslug extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaDodacLogo extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaDodacWWW extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaDodacBanera extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieMoznaWlaczycMailingu extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class BladPobieraniaPakietu extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieprawidloweIdPakietu extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}

class NieDodanoPakietu extends Exception {

    public function __construct() {
        parent::__construct('', 0);
    }

}
?>
