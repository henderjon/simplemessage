<?php

class PlainTextTest extends \PHPUnit_Framework_TestCase {

	function getSample($file){
		return dirname(__FILE__) . "/samples/" . trim($file, "/");
	}

	function test_marshal(){

		$message = new SimpleMessage\PlainMessage;

		$message->setHeaders([
			"from"    => "email@address.com",
			"to"      => "email@address.com",
			"subject" => "this should be plain text",
		]);

		$message->setMessage("this is some <strong>plain</strong> text.");

		$expected = file_get_contents($this->getSample("sample_PlainText.txt"));
		$expected = str_replace("\n", "\r\n", $expected);

		$this->assertEquals($expected, $message->marshal());

	}

}