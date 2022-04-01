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

  protected function action_qrcode()
  {
    $this->maintpl = 'tpl/qrcode.tpl';

    $qr = $this->makeInstance('qrcode', 'Booosta QR-Code Test');
    $qr->save_file('upload/qr.png');
    $qr->show_js('myqr');
  }

  protected function action_rest()
  {
    $url = 'http://api.icndb.com/jokes/random';

    $rest = $this->makeInstance('rest', $url);
    $result = $rest();

    if($error = $rest->get_error()) $this->raise_error($error);

    $this->TPL['output'] = '<pre>' . print_r(json_decode($result, true), true) . '</pre>';

    $jokes = $this->makeInstance('JokeREST');
    $result = $jokes->get_joke();
    $this->TPL['output'] .= "<br><br> <pre>$result</pre>";

    $this->maintpl = 'tpl/rest.tpl';
  }

  protected function action_soap()
  {
    $server = $this->makeInstance('soap', 'https://www.dataaccess.com/webservicesserver/NumberConversion.wso?WSDL');
    $result = $server->NumberToWords(['ubiNum' => 1234]);
    #$this->TPL['output'] .= print_r($result, true);
    $this->TPL['output'] .= "<br><br> <pre>" . $result->NumberToWordsResult . "</pre>";

    $this->maintpl = 'tpl/soap.tpl';
  }

  protected function action_tooltip()
  {
    $tooltip = $this->makeInstance('tooltip', 'tip');
    $tooltip->set_content('<h1>Test</h1>
This is a Test');
    $tooltip->set_position('left');
    $this->TPL['output'] = '<br><br><center>' . $tooltip->get_html() . '</center>';

    $this->maintpl = 'tpl/tooltip.tpl';
  }

  protected function action_sortable()
  {
    $list = ['One', 'Two', 'Three'];
    $sortable = $this->makeInstance('ui_sortable', 'numbers', $list);
    $this->TPL['list'] = $sortable->get_html();

    $table = [['1', 'One', 'Un', 'Eins'], ['2', 'Two', 'Deux', 'Zwei'], ['3', 'Three', 'Trois', 'Drei']];
    $sortable = $this->makeInstance("\\booosta\\ui_sortable\\Ui_sortable_table", $table, true, false, 'test.php?action=sort');  // true = use tabletags, false = use datatable
    $this->TPL['table'] = $sortable->get_html();

    $this->maintpl = 'tpl/sortable.tpl';
  }

  protected function action_sort()
  {
    $origin = $this->VAR['origin'];
    $destination = $this->VAR['destination'];

    b::debug("Sorting $origin to $destination");

    \booosta\ajax\Ajax::print_response('result', '');
    $this->no_output = true;
  }

  protected function action_textarea()
  {
    $area = $this->makeInstance('Ui_textarea', 'testarea', 'Test Content');
    $area->set_counttext('Verfügbare Zeichen:');
    $area->set_max(30);
    $this->TPL['output'] = $area->get_html();

    $this->maintpl = 'tpl/textarea.tpl';
  }

  protected function action_uploader()
  {
    $uploader = $this->makeInstance('uploader', 'file1', 'test.php');
    $uploader->add_hidden('action', 'uploader_upload');
    $this->TPL['output'] = $uploader->get_html();

    $this->maintpl = 'tpl/uploader.tpl';
  }

  protected function action_uploader_upload()
  {
    #\booosta\debug($_FILES);
    $file1 = $this->makeInstance('Uploadfile', 'file1');
    if($file1->is_valid()) $this->TPL['output'] .= 'Uploaded ' . $file1->get_html();

    $this->maintpl = 'tpl/uploader.tpl';
  }

  protected function action_wysiwyg()
  {
    $wysiwyg = $this->makeInstance('Wysiwygeditor', 'classic');
    $wysiwyg->set_content('Test für Classic Editor');
    $wysiwyg->set_language('de');
    $this->TPL['classic'] = $wysiwyg->get_html();

    // only classic OR inline editor is possible on one page
    #$wysiwyg1 = $this->makeInstance('Wysiwygeditor', 'inline');
    #$wysiwyg1->set_content('Test für Inline Editor');
    #$wysiwyg1->set_type('inline');
    #$wysiwyg1->set_ajaxurl('test.php?action=wysiwyg_ajax');
    #$wysiwyg1->set_language('fr');
    #$this->TPL['inline'] = $wysiwyg1->get_html();

    $this->maintpl = 'tpl/wysiwyg.tpl';
  }

  protected function action_wysiwyg_ajax()
  {
    b::debug("Got inline WYSIWYG-Content: " . $this->VAR['content']);

    \booosta\ajax\Ajax::print_response('result', '');
    $this->no_output = true;
  }

  protected function action_wysiwyg_save()
  {
    b::debug("Got classic WYSIWYG-Content: " . $this->VAR['classic']);
    $this->action_wysiwyg();
  }
}

class JokeREST extends \booosta\rest\Application
{
  protected $url = 'http://api.icndb.com/jokes/random';

  public function get_joke()
  {
    $result = $this->get('');
    return print_r($result, true);
  }
}

$a = new Test1();
$a->auth_user();
$a();
