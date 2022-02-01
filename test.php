<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class Test1 extends booosta\usersystem\Webappadmin
{
  public $maintpl = 'tpl/test.tpl';

  protected function action_default()
  { 
    $content = '<h1>Test</h1>Test';
    $popup = $this->makeInstance('bs_modal', 'test');
    $popup->set_content($content);
    $this->TPL['bs_modal_link'] = $popup->get_html_link();

    $cache = $this->makeInstance('Cache_db');
    if($this->VAR['clear_cache']) $cache->clear();
    $this->TPL['cache1'] = 'Cache_db: ' . $cache->get('http://unixtime.icb.at') . '<br>';

    $cache2 = $this->makeInstance('Cache_session');
    if($this->VAR['clear_cache']) $cache2->clear();
    $this->TPL['cache1'] .= 'Cache_Session: ' . $cache2->get('http://unixtime.icb.at') . '<br>Real: ' . time();

    $datepicker = $this->makeInstance('ui_datepicker', 'picker1');
    $this->TPL['datepicker'] = $datepicker->get_html();
  }

  protected function action_email()
  {
    $email = $this->makeInstance('email', 'peter@icb.at', 'peter@internetclub.at', 'Test-Mail', "Testnachricht<br><br>lg, Peter");
    $result = $email->send();

    $this->maintpl = $result;
  }

  protected function action_tabcontainer()
  {
    $tabs = $this->makeInstance('tabcontainer', 'test1');
    $tabs->set_tabs([['name' => 'Eins', 'content' => 'Der erste Content', 'icon' => 'ICON'],
                     ['name' => 'Zwei', 'content' => 'Der zweite Content', 'icon' => 'ICON'],
                     ['name' => 'Drei', 'content' => 'Der dritte Content', 'icon' => 'ICON']]);

    $tabs->set_title('Test-Tabs');
    $this->maintpl = $tabs->get_html();

    $tabs = $this->makeInstance('tabcontainer', 'test2');
    $tabs->set_tabs([['name' => 'Eins', 'content' => 'Der erste Content', 'icon' => 'ICON'],
                     ['name' => 'Zwei', 'content' => 'Der zweite Content', 'icon' => 'ICON'],
                     ['name' => 'Drei', 'content' => 'Der dritte Content', 'icon' => 'ICON']]);

    $tabs->set_type('vertical');
    $this->maintpl .= $tabs->get_html();

    $tabs3 = $this->makeInstance('tabcontainer', 'test3');
    $tabs3->set_tabs([['name' => 'Eins', 'content' => 'Der erste Content', 'icon' => 'ICON'],
                      ['name' => 'Zwei', 'content' => 'Der zweite Content', 'icon' => 'ICON'],
                      ['name' => 'Drei', 'content' => 'Der dritte Content', 'icon' => 'ICON']]);

    $tabs = $this->makeInstance('tabcontainer', 'test4');
    $tabs->set_tabs([['name' => 'Eins', 'content' => 'Der erste Content', 'icon' => 'ICON'],
                     ['name' => 'Zwei', 'content' => $tabs3, 'icon' => 'ICON'],
                     ['name' => 'Drei', 'content' => 'Der dritte Content', 'icon' => 'ICON']]);

    $tabs->set_type('vertical');
    $this->maintpl .= '<br><br>' . $tabs->get_html();
  }

  protected function action_fileupload()
  {
    $this->maintpl = 'tpl/file.tpl';
  }

  protected function action_upload()
  {
    #\booosta\debug($_FILES);

    $file1 = $this->makeInstance('Uploadfile', 'file1');
    $file2 = $this->makeInstance('Uploadfile', 'file2', 'upload/', true);
    $file3 = $this->makeInstance('Uploadfile', 'file3', 'upload/', $this->VAR['filename']);

    $file4 = $this->makeInstance('Imagefile', 'file4');
    if($file4->is_valid()):
      $info = $file4->get_width() . ' x ' . $file4->get_height();
      $file4->resize(300, 300, 'max');
      $info .= ' -> ' . $file4->get_width() . ' x ' . $file4->get_height();
    endif;

    $file5 = $this->makeInstance('Uploadfiles', 'file5');
    $file6 = $this->makeInstance('Uploadfile', 'file6');
    #$this->watermark_image($file6->get_url(), 'upload/watermark.png');

    if($file1->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file1->get_html();
    if($file2->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file2->get_html();
    if($file3->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file3->get_html();
    if($file4->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file4->get_html();
    if($file5->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file5->get_html();
    if($file6->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file6->get_html();

    $this->TPL['output'] .= "<br>$info";
    $this->maintpl = 'tpl/simple.tpl';
  }
}

$a = new Test1();
$a();

