{BBOXCENTER}
{BPANEL|paneltitle::Edit Compactdisc}

{BFORMSTART|compactdisc}
{HIDDEN|action|editdo}
{HIDDEN|object_id|{%id}}
{HIDDEN|form_token|{%form_token}}

{BTEXT|name|{*name}|texttitle::Name}
{BTEXT|artist|{*artist}|texttitle::Artist}
{BSELECT|genre|{%genre}|texttitle::Genre
pop
rock
classic
other}
{BDATE|publication|{*publication}|texttitle::Publication}
{BTEXTAREA|description|10|texttitle::Description
{*description}}
{BCHECKBOX|cdr|{%cdr}|texttitle::Cdr}


{BFORMSUBMIT|class::center-block}
{BFORMEND}
{BLINK|Back|javascript:history.go(-1);}

{/BPANEL}
{/BBOXCENTER}

%if({%subtables_in_edit}):
  {BBOXCENTER|bboxsize::12}
  {BPANEL|paneltitle::Song}

  {BLINKADD|New song|song/new/{%id}}

  {%subliste}
  {/BPANEL}
  {/BBOXCENTER}
%endif;
