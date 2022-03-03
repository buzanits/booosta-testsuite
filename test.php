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
    $captcha = $this->makeInstance('Captcha', 'mailcaptcha');
    $this->TPL['captcha'] = $captcha->get_html();

    $this->maintpl = 'tpl/email.tpl';
  }

  protected function action_emailsend()
  {
    if(!$this->check_captcha('mailcaptcha')) $this->raise_error('Wrong Captcha!'); 

    $msg = "<html><body>" . $this->VAR['message'] . "<br><img src='cid:Testimage'></body></html>";
    $mail = $this->makeInstance('email', $this->VAR['sender'], $this->VAR['recipient'], 'Testmail', $msg);

    if($this->VAR['use_smtp']):
      $mail->set_backend('smtp');
      $mail->set_smtp_params(['host' => $this->VAR['smtp_server'], 'auth' => true, 'username' => $this->VAR['smtp_username'], 'password' => $this->VAR['smtp_password']]);
    endif;

    $file1 = $this->makeInstance('Uploadfile', 'file1', 'upload/', true);
    if($file1->is_valid()) $mail->set_attachments(array($file1->get_filename() => $file1->get_url()));

    $file1 = $this->makeInstance('Uploadfile', 'picture1');
    if($file1->is_valid()) $mail->set_images(['Testimage' => $file1->get_url()]);

    $result = $mail->send();

    if($result === true) $this->TPL['output'] = 'Mail sent successfully';
    else $this->TPL['output'] = "Error: " . print_r($result, true);

    $this->maintpl = booosta\webapp\FEEDBACK;
    $this->backpage = 'test/email';
    $this->goback = false;
  }

  protected function action_tabcontainer()
  {
    $icon = '<i class="fas fa-cogs"></i>';

    $tabs = $this->makeInstance('tabcontainer', 'test1');
    $tabs->set_tabs([['name' => 'One', 'content' => 'The first Content', 'icon' => $icon],
                     ['name' => 'Two', 'content' => 'The second Content', 'icon' => $icon],
                     ['name' => 'Three', 'content' => 'The third Content', 'icon' => $icon]]);

    $tabs->set_title('Test-Tabs');
    $this->maintpl = $tabs->get_html();

    $tabs = $this->makeInstance('tabcontainer', 'test2');
    $tabs->set_tabs([['name' => 'One', 'content' => 'The first Content', 'icon' => $icon],
                     ['name' => 'Two', 'content' => 'The second Content', 'icon' => $icon],
                     ['name' => 'Three', 'content' => 'The third Content', 'icon' => $icon]]);

    $tabs->set_type('vertical');
    $this->maintpl .= $tabs->get_html();

    $tabs3 = $this->makeInstance('tabcontainer', 'test3');
    $tabs3->set_tabs([['name' => 'One', 'content' => 'The first Content', 'icon' => $icon],
                      ['name' => 'Two', 'content' => 'The second Content', 'icon' => $icon],
                      ['name' => 'Three', 'content' => 'The third Content', 'icon' => $icon]]);

    $tabs = $this->makeInstance('tabcontainer', 'test4');
    $tabs->set_tabs([['name' => 'One', 'content' => 'The first Content', 'icon' => $icon],
                     ['name' => 'Two', 'content' => $tabs3, 'icon' => $icon],
                     ['name' => 'Three', 'content' => 'The third Content', 'icon' => $icon]]);

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

  protected function action_captcha()
  {
    $captcha = $this->makeInstance('captcha');
    #$captcha = $this->makeInstance('Captcha', 'mycaptcha');
    $this->TPL['captcha'] = $captcha->get_html();

    $this->maintpl = 'tpl/captcha.tpl';
  }

  protected function action_checkcaptcha()
  {
    $result = $this->TPL['result'] = $this->check_captcha() ? 'right' : 'wrong';
    #$this->TPL['result'] = $this->check_captcha('mycaptcha') ? 'right' : 'wrong';

    if($result == 'right') $this->add_notification('A correct captcha has been entered', null, 'success', false);  // text, user, type, autoseen
    else $this->add_notification('A wrong captcha has been entered', null, 'danger', false);  // text, user, type, autoseen

    $this->maintpl = 'tpl/captcharesult.tpl';
  }

  protected function action_excel()
  {
    $this->maintpl = 'tpl/excel.tpl';
  }

  protected function action_readexcel()
  {
    $xls = $this->makeInstance('Uploadfile', 'xlsfile');
    $reader = $this->makeInstance('Spreadsheet', $xls->get_url());
    $reader->extract_hyperlinks();
    $data = $reader->get_indexed_data(false, true);  // param: convert to utf8, use header

    $table = $this->makeInstance('Tablelister', $data, true);  // last param: tabletags
    $table->set_header($reader->get_header());
    $table->always_show_header(true);
    $table->set_th_attribute('style', 'border: 1px solid black;');
    $table->set_data_attribute('style', 'border: 1px solid black;');

    $this->TPL['liste'] = $table->get_html();

    $this->maintpl = 'tpl/excel_show.tpl';
  }

  protected function action_ftp()
  {
    $this->maintpl = 'tpl/ftp.tpl';
  }

  protected function action_ftpload()
  {
    $v = $this->VAR;
    $ftp = $this->makeInstance('ftp', $v['server'], $v['user'], $v['password'], ['plaintext' => true]);
    #$ftp = $this->makeInstance('ftp', $v['server'], $v['user'], $v['password'], ['implicit_tls' => true]);
    #$ftp = $this->makeInstance('ftp', $v['server'], $v['user'], $v['password']);
    if($error = $ftp->get_error()) $this->raise_error("FTP connect failed: $error");

    $localfile = basename($v['file']);
    $ftp->download($v['file'], "upload/$localfile");

    $this->download_file("upload/$localfile");
    $this->maintpl = 'tpl/ftp.tpl';
  }

  protected function action_graph()
  {
    $data = [[[0,2], [1,4], [2,1]], [[0,3], [1,3], [2,0]]];
    $chart = $this->makeInstance('graph1', 'testgraph', $data);
    $chart->set_option('yaxis', 'max', 10);
    $chart->set_title('Test-Chart');
    $chart->set_width(600);
    $chart->set_height(200);
    $chart->set_colors(['#FF0000', '#0000FF']);
    $this->TPL['chart'] = $chart->get_html();

    $data = array('2010' => 150, '2011' => 130, '2012' => 177, '2013' => 170, '2014' => 160);
    $chart = $this->makeInstance("booosta\\graph\\Barchart", $data);
    $chart->set_title('Test-Bar-Chart');
    $this->TPL['chartlink'] = $chart->get_link();
    $this->TPL['barchart'] = $chart->get_html();

    $data = array('2010' => 15, '2011' => 130, '2012' => 70, '2013' => 70, '2014' => 100);
    $chart = $this->makeInstance("booosta\\graph\\Piechart", $data);
    $chart->set_title('Test-Pie-Chart');
    $this->TPL['chartlink'] = $chart->get_link();
    $this->TPL['piechart'] = $chart->get_html();

    $this->maintpl = 'tpl/graph.tpl';
  }

  protected function action_image()
  {
    copy('tpl/testimage_orig.jpg', 'upload/testimage.jpg');
    $image = $this->makeInstance('image', 'upload/testimage.jpg');
    $image->resize(300, 300);

    $this->TPL['newname'] = $image->get_filename();

    $this->maintpl = 'tpl/image.tpl';
  }

  protected function action_imageselect()
  {
    $options = ['1' => 'Grand Canyon', '2' => 'Yellowstone', '3' => 'Yosemite'];
    $select = $this->makeInstance('Imageselect', 'test', $options, ['2', '3']);
    $select->set_images(['1' => 'tpl/grandcanyon.png', '2' => 'tpl/yellowstone.png', '3' => 'tpl/yosemite.png']);

    $this->TPL['output'] = $select->get_html();
    $this->maintpl = 'tpl/imageselect.tpl';
  }

  protected function action_sendimageselect()
  {
    $this->maintpl = '<pre>' . print_r($this->VAR, true) . '</pre>';
  }

  protected function action_imap()
  {
    $this->maintpl = 'tpl/imap.tpl';
  }

  protected function action_retrieveimap()
  {
    $imap = $this->makeInstance('imap', $this->VAR['server'], $this->VAR['username'], $this->VAR['password'], true);
    if($error = $imap->error()) $this->raise_error("This error occured: $error");

    $msg = $imap->get_last_message();
    $this->TPL['sender'] = $msg->get_sender();
    $this->TPL['subject'] = $msg->get_subject();

    $this->maintpl = 'tpl/imapshow.tpl';
  }

  protected function action_openstreetmap()
  {
    $lat = $this->VAR['lat'] ?? 37.82;
    $lon = $this->VAR['lon'] ?? -122.4782;
    if($lat === '') $lat = 37.82;
    if($lon === '') $lon = -122.4782;

    #b::debug("lat: $lat, lon: $lon");
    $map = $this->makeInstance('openstreetmap_org', $lat, $lon, 12);  // lat, lon, zoom (default=15)
    $map->height('300px');
    $map->width('400px');
    $map->add_marker();  // default in center of map
    #$map->add_marker($lat, $lon);

    $this->TPL['map'] = $map->get_html();
    $this->maintpl = 'tpl/openstreetmap.tpl';
  }

  protected function action_pdf()
  {
     $this->maintpl = 'tpl/pdfwriter.tpl';
  }

  protected function action_showpdf()
  {
    $writer = $this->makeInstance('Pdfwriter', $this->VAR['html']);

    $picture = $this->makeInstance('Uploadfile', 'picture');
    if($picture->is_valid()) $writer->addImage($picture->get_url(), 20, 20);

    #$writer->save('/var/www/fw3test/test001.pdf');
    $writer->download('test.pdf');
  }
}

$a = new Test1();
$a->auth_user();
$a();
