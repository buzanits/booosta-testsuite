{BBOXCENTER|bboxsize::12}
{BPANEL|paneltitle::E-Mail}

{FORMSTARTM|test}
{HIDDEN|action|emailsend}

{BTEXT|sender}
{BTEXT|recipient}
{BTEXTAREA|message|texttitle::message}
{BFORMGRP|file}{FILE|file1}{/BFORMGRP}
{BFORMGRP|picture}{FILE|picture1}{/BFORMGRP}
{BCHECKBOX|use_smtp|texttitle::use SMTP}
{BTEXT|smtp_server|texttitle::SMTP server}
{BTEXT|smtp_username|texttitle::SMTP user}
{BPASSWORD|smtp_password|texttitle::SMTP password}
Type the following text:<br>
{%captcha}

{FORMSUBMIT}
{/FORM}

{/BPANEL}
{/BBOXCENTER}