<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class App extends booosta\usersystem\Webappadmin
{
  use \booosta\calendar\actions;

  #protected $fields = 'name,edit,delete';
  #protected $header = 'Name,Edit,Delete';
  protected $use_subtablelink = false;
  
  protected $checkbox_fields = 'allday,readonly';
  protected $urlhandler_action_paramlist = ['new' => 'action/startdate'];
  
  
  protected function action_default()
  {
    $calendar = $this->makeInstance('fullcalendar');
    $calendar->set_baseurl('/event.php');
    #if($_SESSION['calendar_view']) $view = $_SESSION['calendar_view']; else $view = 'agendaWeek';

    $calendar->set_lang('de');
    $calendar->set_availableViews('dayGridMonth,timeGridWeek,timeGridDay,listMonth');
    $calendar->set_defaultview($view);
    $calendar->set_eventBackgroundColor('purple');

    $calendar->hide_days('0');
    $calendar->set_minTime('08:00');
    $calendar->set_maxTime('19:00');
    $calendar->set_slotDuration('00:30:00');

    $calendar->set_eventClickCode(true);
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
    $this->set_event_dates(60);
  }

  protected function before_action_edit()
  {
    $picker = $this->makeInstance('colorpicker', 'color', $this->get_data('color'));
    $this->TPL['colorsel'] = $picker->get_html();
  }
}

$app = new App('event');

$app->auth_user();
$app();
