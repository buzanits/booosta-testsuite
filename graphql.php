<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\graphql\GraphqlException;
use booosta\Framework as b;
b::load();

class App extends \booosta\graphql\Graphql
{
  protected $debugmode = true;
  #protected $datafields = 'login,createEvent,user,ticketcomment,ticketattachment';
  protected $public_datafields = 'test,compactdisc,song';
  #protected $public_editfields = 'test';

  protected $subitems = ['compactdisc' => ['song']];
  protected $foreignkeys = ['song' => ['compactdisc']];
  protected $use__call = true;
}

unset($_SESSION['AUTH_USER']);   // avoid using logged in user when testing in browser
$app = new App();
$app();
