{BBOXCENTER}
{BPANEL|paneltitle::Edit Event}

{BFORMSTART|event}
{HIDDEN|action|editdo}
{HIDDEN|object_id|{%id}}
{HIDDEN|form_token|{%form_token}}

{BTEXT|name|{*name}|texttitle::Name}
{BTEXT|startdate|{*startdate}|texttitle::Startdate}
{BTEXT|enddate|{*enddate}|texttitle::Enddate}
{%colorsel}
{BCHECKBOX|readonly|{%readonly}|texttitle::Read only}
{BCHECKBOX|allday|{%allday}|texttitle::All day event}


{BFORMSUBMIT|class::center-block}
{BFORMEND}
{BLINK|Back|javascript:history.go(-1);}

{/BPANEL}
{/BBOXCENTER}
