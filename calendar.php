<?php
require_once __DIR__ . '/vendor/autoload.php';

use booosta\Framework as b;
b::load();

class Test1 extends booosta\usersystem\Webappadmin
{
  public $maintpl = 'tpl/calendar.tpl';

  protected function action_default()
  { 
    $calendar = $this->makeInstance('fullcalendar');
    if($_SESSION['calendar_view']) $view = $_SESSION['calendar_view']; else $view = 'agendaWeek';

    $calendar->set_lang('de');
    $calendar->set_availableViews('month,agendaWeek,agendaDay,listMonth');
    $calendar->set_defaultview($view);
    $calendar->set_eventBackgroundColor('blue');

    $calendar->hide_days('0');
    $calendar->set_minTime('08:00');
    $calendar->set_maxTime('19:00');
    $calendar->set_slotDuration('00:30:00');

    $calendar->set_dayClickCode(true);
    $calendar->set_dragDropCode(true);
    $calendar->set_resizeCode(true);
    #$calendar->set_dayClickCode('window.location.href="user_appointment.php?action=new&dtime=" + clicked_date;');
    #$calendar->set_dragDropCode('$.ajax("user_appointment.php?action=move&object_id=" + event_id + "&dtime=" + new_starttime);');
    #$calendar->set_resizeCode('$.ajax("user_appointment.php?action=resize&object_id=" + event_id + "&endtime=" + new_endtime);');

  }
}

$a = new Test1();
$a();

