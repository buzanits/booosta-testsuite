{BBOXCENTER}
{BPANEL|paneltitle::Edit Song}

{BFORMSTART|song}
{HIDDEN|action|editdo}
{HIDDEN|object_id|{%id}}
{HIDDEN|form_token|{%form_token}}

{BTEXT|name|{*name}|texttitle::Name}
{BTEXT|length|{*length}|texttitle::Length}
{BTEXT|number|{*number}|texttitle::Number}


{BFORMSUBMIT|class::center-block}
{BFORMEND}
{BLINK|Back|javascript:history.go(-1);}

{/BPANEL}
{/BBOXCENTER}
