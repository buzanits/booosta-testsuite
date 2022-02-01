<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class App extends booosta\usersystem\Webappadmin
{
  #protected $fields = 'name,edit,delete';
  #protected $header = 'Name,Edit,Delete';
  protected $use_subtablelink = false;
  
  #protected $checkbox_fields = '';
  
  
  
  
  protected $urlhandler_action_paramlist = ['new' => 'action/compactdisc'];
}

$app = new App('song');
$app->set_supername('compactdisc');

$app->auth_user();
$app();
