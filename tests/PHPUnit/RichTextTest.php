<?php

class RichTextTest extends \PHPUnit_Framework_TestCase {

	function getSample($file){
		return dirname(__FILE__) . "/samples/" . trim($file, "/");
	}

	function test_marshal1(){

		$message = new SimpleMessage\RichMessage;

		$message->setHeaders([
			"from"    => "email@address.com",
			"to"      => "email@address.com",
			"subject" => "this should be rich text",
		]);

		$message->setMessage("this is some <strong>html</strong> text.");

		$expected = file_get_contents($this->getSample("sample_RichText.txt"));
		$expected = str_replace("\n", "\r\n", $expected);

		$cleanups = [
			"!boundary=\"\w+\"!i" => "boundary=\"SHA1-HASH\"",
			"!--\w+!i" => "--SHA1-HASH",
		];

		$marshaled = preg_replace(array_keys($cleanups), array_values($cleanups), $message->marshal());

		$this->assertEquals($expected, $marshaled);

	}

	function test_marshal2(){

		$message = new SimpleMessage\RichMessage;

		$message->setHeaders([
			"from"    => "email@address.com",
			"to"      => "email@address.com",
			"subject" => "this should be rich text w/ an attachment",
		]);

		$message->setMessage("this is some <strong>html</strong> text.");
		$message->attachFile($this->getSample("sample_attachment.txt"));

		$expected = file_get_contents($this->getSample("sample_RichTextAttachment.txt"));
		$expected = str_replace("\n", "\r\n", $expected);

		$cleanups = [
			"!boundary=\"\w+\"!i" => "boundary=\"SHA1-HASH\"",
			"!--\w+!i" => "--SHA1-HASH",
		];

		$marshaled = preg_replace(array_keys($cleanups), array_values($cleanups), $message->marshal());

		// drop($expected, $marshaled);

		$this->assertEquals($expected, $marshaled, "random/unique sha1s are difficult to test against.");

	}

	function test_marshal3(){

		$message = new SimpleMessage\RichMessage;

		$message->setFrom("email@address.com")
			->setTo(["email@address.com"])
			->setReplyTo(["email@address.com"])
			->setCc(["email@address.com"])
			->setBcc(["email@address.com"])
			->setReturnPath("email@address.com")
			->setSubject("this should be rich text w/ all headers")
			->setMessage("this is some <strong>html</strong> text.");

		$expected = file_get_contents($this->getSample("sample_RichTextAllHeaders.txt"));
		$expected = str_replace("\n", "\r\n", $expected);

		$cleanups = [
			"!boundary=\"\w+\"!i" => "boundary=\"SHA1-HASH\"",
			"!--\w+!i" => "--SHA1-HASH",
		];

		$marshaled = preg_replace(array_keys($cleanups), array_values($cleanups), $message->marshal());

		// drop($expected, $marshaled);

		$this->assertEquals($expected, $marshaled, "random/unique sha1s are difficult to test against.");

	}

}