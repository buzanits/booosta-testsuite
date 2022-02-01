{BBOXCENTER}
{BPANEL|paneltitle::New Compactdisc}

{BFORMSTART|compactdisc}
{HIDDEN|action|newdo}
{HIDDEN|form_token|{%form_token}}

{BTEXT|name|texttitle::Name}
{BTEXT|artist|texttitle::Artist}
{BSELECT|genre|texttitle::Genre
pop
rock
classic
other}
{BDATE|publication|texttitle::Publication}
{BTEXTAREA|description|10|texttitle::Description}
{BCHECKBOX|cdr|texttitle::Cdr}

{BFORMSUBMIT|class::center-block}
{BFORMEND}
{BLINK|Back|javascript:history.go(-1);}

{/BPANEL}
{/BBOXCENTER}
