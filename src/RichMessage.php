<?php

namespace SimpleMessage;

class RichMessage extends PlainMessage {
	/**
	 * we do attachments now
	 */
	use AttachmentTrait;
	/**
	 * method to create a *unique* (for our purposes) boundary string
	 * @return string
	 */
	protected function makeBoundary(){
		return sha1(time() . mt_rand(1,9999));
	}
	/**
	 * method to create a message that contains both plain text and html
	 * @param string $boundary The boundary to use when creating the message
	 * @param mixed $makeSubPart If truthy, create the message as a subpart
	 * @return string
	 */
	function assembleBody($boundary, $makeSubPart = false){

		$body = "";

		if($makeSubPart){
			$body     .= "--" . $boundary . $this->delim;
			//create a new boundary
			$boundary  = md5($boundary);
			$body     .= $this->formatHeader("content-type", "multipart/alternative; boundary=\"{$boundary}\"");
			$body     .= $this->delim;
		}else{
			$this->setHeaders(["content-type" => "multipart/alternative; boundary=\"{$boundary}\""]);
		}

		$body .= "--" . $boundary . $this->delim;
		$body .= $this->formatHeader("content-type", "text/plain; charset=utf-8");
		$body .= $this->formatHeader("content-disposition", "inline");
		$body .= $this->delim;
		$body .= strip_tags($this->message) . $this->delim;;
		$body .= "--" . $boundary . $this->delim;
		$body .= $this->formatHeader("content-type", "text/html; charset=utf-8");
		$body .= $this->formatHeader("content-disposition", "inline");
		$body .= $this->delim;
		$body .= $this->message . $this->delim;;
		$body .= "--" . $boundary . "--";
		return $body;
	}
	/**
	 * method to order the pieces and out up the message as a string.
	 * @return string
	 */
	function marshal(){

		$this->setHeaders(["MIME-Type" => "1.0"]);

		$boundary = $this->makeBoundary();

		$attachments = "";
		if($this->attachments){
			$attachments = $this->assembleAttachments($boundary);
		}

		$body    = $this->assembleBody($boundary, count($this->attachments));
		$headers = $this->assembleHeaders();

		$message = "";
		$message .= $headers;
		$message .= $this->delim;
		$message .= $body;
		$message .= $this->delim;
		$message .= $attachments;
		return $message;
	}
}