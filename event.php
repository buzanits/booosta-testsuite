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
  protected $urlhandler_action_paramlist = ['new' => 'action/startdate'];
  
  
  protected function action_default()
  {
    $calendar = $this->makeInstance('fullcalendar');
    #if($_SESSION['calendar_view']) $view = $_SESSION['calendar_view']; else $view = 'agendaWeek';

    $calendar->set_lang('de');
    #$calendar->set_availableViews('month,agendaWeek,agendaDay,listMonth');
    $calendar->set_defaultview($view);
    $calendar->set_eventBackgroundColor('blue');

    $calendar->hide_days('0');
    $calendar->set_minTime('08:00');
    $calendar->set_maxTime('19:00');
    $calendar->set_slotDuration('00:30:00');

    $calendar->set_dayClickCode(true);
    $calendar->set_dragDropCode(true);
    $calendar->set_resizeCode(true);
    // individual Javascript for click, drag and resize
    #$calendar->set_dayClickCode('window.location.href="myscript.php?action=new&dtime=" + clicked_date;');
    #$calendar->set_dragDropCode('$.ajax("myscript.php?action=move&object_id=" + event_id + "&dtime=" + new_starttime);');
    #$calendar->set_resizeCode('$.ajax("myscript.php?action=resize&object_id=" + event_id + "&endtime=" + new_endtime);');

    $calendar->load_events();
    $this->TPL['fullcalendar'] = $calendar->get_html();
    $this->maintpl = 'tpl/event_default.tpl';
  }
  
  protected function before_action_new()
  {
    $this->pass_vars_to_template('startdate');
  }
}

$app = new App('event');

$app->auth_user();
$app();
