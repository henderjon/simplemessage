<?php

require "vendor/autoload.php";

/**
 * RichText Raw Message
 */

$message = new SimpleMessage\RichMessage;

$message->setHeaders([
	"from"    => "email@address.com",
	"to"      => "email@address.com",
	"subject" => "this should be rich text w/ an attachment",
]);

$message->setMessage("this is some <strong>html</strong> text.");

$message->attachFile("./examples/attachment.txt");

print_r($message->marshal());

/**
 * AWS SES Message
 */

$config = array(
	"access_key" => "",
	"secret_key" => "",
	"region"     => "",
);

//overwrite our examples
if(file_exists("conf/config.ini")){
	$config = parse_ini_file("conf/config.ini");
}

use \Aws\Common\Aws;

// Instantiate an S3 client
$aws = Aws::factory(array(
    "key"    => $config["access_key"],
    "secret" => $config["secret_key"],
    "region" => $config["region"],
));

$ses = $aws->get('ses');

$message = new SimpleMessage\SESMessage;

// this is NOT a valid, verified email addess
$message->setHeaders([
	"from"    => "email@address.com",
	"to"      => "email@address.com",
	"subject" => "this should be rich text w/ an attachment",
]);

$message->setMessage("this is some <strong>html</strong> text.");

$response = $ses->sendEmail($message->marshal());

print_r($response);

