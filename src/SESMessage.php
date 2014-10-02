<?php

namespace SimpleMessage;

class SESMessage extends PlainMessage {

	protected $charset = "UTF-8";
	/*
	 * for docs, check \Chevron\Mailer\Interfaces\MessageInterface
	 */
	function marshal(){

		if(!$this->getHeader("from")){
			throw new \Exception("You're missing the required 'From' property");
		}

		$message = array();
		$message["Source"]             = $this->getHeader("from");
		$message["Destination"]        = $this->assembleDestination();
		$message["Message"]["Subject"] = $this->assembleSubject();
		$message["Message"]["Body"]    = $this->assembleBody();

		if($replyTo = $this->getHeader("reply-to")){ // not required
			$message["ReplyToAddresses"] = (array)$replyTo;
		}

		if($returnPath = $this->getHeader("return-path")){ // not required
			$message["ReturnPath"] = $returnPath;
		}

		return $message;

	}
	/**
	 * Method to assemble the "Destination" block using the To, Cc, and Bcc
	 * fields.
	 *
	 * @link http://docs.aws.amazon.com/ses/latest/DeveloperGuide/limits.html
	 *
	 * @return array
	 */
	protected function assembleDestination(){

		if(!$this->getHeader("to")){
			throw new \Exception("You're missing the required 'To' property");
		}

		$total = 0;

		$destination = array("ToAddresses" => (array)$this->getHeader("to"));
		$total += count($this->getHeader("to"));

		if($this->getHeader("cc")){
			$destination["CcAddresses"] = (array)$this->getHeader("cc");
			$total += count($this->getHeader("cc"));
		}

		if($this->getHeader("bcc")){
			$destination["BccAddresses"] = (array)$this->getHeader("bcc");
			$total += count($this->getHeader("bcc"));
		}

		if($total > 50){
			throw new \Exception("An SES message can have no more than 50 recipients.");
		}

		return $destination;
	}
	/**
	 * Method to assemble the "Message => Subject" block using the Subject and Charset
	 * fields.
	 * @return array
	 */
	protected function assembleSubject(){

		if(!$this->getHeader("subject")){
			throw new \Exception("You're missing the required 'Subject' property");
		}

		return array(
			"Data"    => $this->getHeader("subject"),
			"Charset" => $this->charset,
		);
	}
	/**
	 * Method to assemble the "Message => Body" block using the Body and/or TextBody
	 * fields.
	 * @return array
	 */
	protected function assembleBody(){

		if(empty($this->message)){
			throw new \Exception("You're missing the required 'Body' property.");
		}

		$body = array();

		if($this->message){
			$body = array(
				"Html" => array(
					"Data"    => $this->message,
					"Charset" => $this->charset,
				),
				"Text" => array(
					"Data"    => strip_tags($this->message),
					"Charset" => $this->charset,
				),
			);
		}

		return $body;
	}
}