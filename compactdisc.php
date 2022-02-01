<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class App extends booosta\usersystem\Webappadmin
{
  protected $fields = 'name,artist,genre,edit,delete';
  #protected $header = 'Name,Edit,Delete';
  protected $sub_fields = 'name,number,edit,delete';
  protected $use_subtablelink = false;
  
  protected $checkbox_fields = 'cdr';
  protected $null_fields = ['publication','description'];
  protected $sub_default_order = ['number'];
  
  
  
}

$app = new App('compactdisc');
$app->set_subname('song');

$app->auth_user();
$app();
