<?php

class SESMessageTest extends \PHPUnit_Framework_TestCase {

	function test_SESMessage1(){
		$message = new \SimpleMessage\SESMessage;
		$message->setFrom("from@email.com");
		$message->setTo(["to1@email.com", "to2@email.com"]);
		$message->setCc(["cc1@email.com"]);
		$message->setCc(["cc2@email.com"], true);
		$message->setBcc(["bcc@email.com"]);
		$message->setReplyTo(["replyto@email.com"]);
		$message->setReturnPath("returnpath@email.com");
		$message->setSubject("THIS IS A SUBJECT");
		$message->setMessage("<strong>OMG!</strong> It's like, <b>WOAH!</b> For real, I'm kinda <i>freaking</i> out right now.");

		$expected = array(
			"Source"      => "from@email.com",
			"Destination" => array(
				"ToAddresses"  => ["to1@email.com", "to2@email.com"],
				"CcAddresses"  => ["cc1@email.com", "cc2@email.com"],
				"BccAddresses" => ["bcc@email.com"],
			),
			"Message" => array(
				"Subject" => array(
					"Data" => "THIS IS A SUBJECT",
					"Charset" => "UTF-8",
				),
				"Body" => array(
					'Html' => array(
						"Data" => "<strong>OMG!</strong> It's like, <b>WOAH!</b> For real, I'm kinda <i>freaking</i> out right now.",
						"Charset" => "UTF-8",
					),
					'Text' => array(
						"Data" => "OMG! It's like, WOAH! For real, I'm kinda freaking out right now.",
						"Charset" => "UTF-8",
					),
				),
			),
			"ReplyToAddresses" => ["replyto@email.com"],
			"ReturnPath"       => "returnpath@email.com",
		);

		$this->assertEquals($expected, $message->marshal());
	}

	function test_SESMessage2(){
		$message = new \SimpleMessage\SESMessage;
		$message->setFrom("from@email.com");
		$message->setTo(["to1@email.com", "to2@email.com"]);
		// $message->setCc(["cc1@email.com"]);
		// $message->setCc(["cc2@email.com"], true);
		// $message->setBcc(["bcc@email.com"]);
		// $message->setReplyTo(["replyto@email.com"]);
		// $message->setReturnPath("returnpath@email.com");
		$message->setSubject("THIS IS A SUBJECT");
		$message->setMessage("<strong>OMG!</strong> It's like, <b>WOAH!</b> For real, I'm kinda <i>freaking</i> out right now.");

		$expected = array(
			"Source"      => "from@email.com",
			"Destination" => array(
				"ToAddresses"  => ["to1@email.com", "to2@email.com"],
				// "CcAddresses"  => ["cc1@email.com", "cc2@email.com"],
				// "BccAddresses" => ["bcc@email.com"],
			),
			// "ReplyToAddresses" => ["replyto@email.com"],
			// "ReturnPath"       => "returnpath@email.com",
			"Message" => array(
				"Subject" => array(
					"Data" => "THIS IS A SUBJECT",
					"Charset" => "UTF-8",
				),
				"Body" => array(
					'Html' => array(
						"Data" => "<strong>OMG!</strong> It's like, <b>WOAH!</b> For real, I'm kinda <i>freaking</i> out right now.",
						"Charset" => "UTF-8",
					),
					'Text' => array(
						"Data" => "OMG! It's like, WOAH! For real, I'm kinda freaking out right now.",
						"Charset" => "UTF-8",
					),
				),
			),
		);

		$this->assertEquals($expected, $message->marshal());
	}

}