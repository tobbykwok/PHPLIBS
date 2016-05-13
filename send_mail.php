<?php
/* 要先配置php.ini 和 sendmail / mecury*/
$mail_to = [
	"Tobby<tobbyguo@hotmail.com>"
];
$subject = "Testing PHP Email Send";
$additional_headers = [
	"From: phpmail@tobbyinside.com",
	"X-Mailer: PHP/".phpversion()
];
$message = "
123
";

echo mail(
	"Tobby<tobbyguo@hotmail.com>", $subject, $message, implode("\r\n",$additional_headers)
);