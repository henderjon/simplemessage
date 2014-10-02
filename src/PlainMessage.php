<?php

namespace SimpleMessage;

class PlainMessage {
	/**
	 * some explicit header setters aren't a bad idea
	 */
	use CommonHeadersTrait;
	/**
	 * the array of message headers
	 */
	protected $headers = array();
	/**
	 * the body of the message
	 */
	protected $message;
	/**
	 * the line ending delimiter
	 */
	protected $delim = "\r\n";
	/**
	 * method to set a message header
	 * @param array $header an array of header-name => value
	 * @return
	 */
	function setHeaders(array $header){
		foreach($header as $key => $value){
			$this->headers[strtolower($key)] = $value;
		}
	}
	/**
	 * allows internal access to headers
	 * @param string $header The name of the header to get
	 * @return mixed
	 */
	protected function getHeader($key){
		$key = strtolower($key);
		if(!array_key_exists($key, $this->headers)){ return null; }
		return $this->headers[$key];
	}
	/**
	 * method to set the message property
	 * @param string $message The message
	 * @return
	 */
	function setMessage($message){
		$this->message = $message;
	}
	/**
	 * method to format a message header
	 * @param string $key The message header value
	 * @param mixed $value The value of the header. Arrays are imploded via comma
	 * @return string
	 */
	protected function formatHeader($key, $value){
		if(is_array($value)){
			$value = implode(", ", $value);
		}
		$value = strtr($value, "\r\n\t", "   ");
		return sprintf("%s: %s%s", ucwords($key), trim($value), $this->delim);
	}
	/**
	 * method to take the $headers array and combine it into a header block for the message
	 * @return string
	 */
	protected function assembleHeaders(){

		$headers = "";

		//recommended order
		$ordered = array("return-path", "received", "date", "from", "subject", "sender", "to", "cc", "bcc");

		foreach($ordered as $key){
			if(array_key_exists($key, $this->headers)){
				$headers .= $this->formatHeader($key, $this->headers[$key]);
			}
		}

		//collapse the rest
		foreach($this->headers as $header => $value){
			if(!in_array($header, $ordered)){
				$headers .= $this->formatHeader($header, $value);
			}
		}

		return $headers;
	}
	/**
	 * method to order the pieces and out up the message as a string.
	 * @return string
	 */
	function marshal(){

		$this->setHeaders(["content-type" => "text/plain; charset=utf-8"]);

		$headers = $this->assembleHeaders();

		$message = "";
		$message .= $headers;
		$message .= $this->delim;
		$message .= strip_tags($this->message);
		$message .= $this->delim;
		return $message;
	}
}

