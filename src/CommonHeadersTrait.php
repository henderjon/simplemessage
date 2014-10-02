<?php

namespace SimpleMessage;

trait CommonHeadersTrait {
	/**
	 * method to set the FROM header
	 * @param string $from The header value
	 * @return $this
	 */
	function setFrom($from){
		$this->setHeaders(["from" => $from]);
		return $this;
	}
	/**
	 * method to set the TO header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setTo(array $addresses, $append = false){
		$current = array();
		if($append){
			$current = (array)$this->headers["to"];
		}
		$to = array_merge($current, $addresses);
		$to = array_filter(array_unique($to));
		$this->setHeaders(["to" => $to]);
		return $this;
	}
	/**
	 * method to set the REPLY-TO header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setReplyTo(array $addresses, $append = false){
		$current = array();
		if($append){
			$current = (array)$this->headers["reply-to"];
		}
		$replyTo = array_merge($current, $addresses);
		$replyTo = array_filter(array_unique($replyTo));
		$this->setHeaders(["reply-to" => $replyTo]);
		return $this;
	}
	/**
	 * method to set the CC header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setCc(array $addresses, $append = false){
		$current = array();
		if($append){
			$current = (array)$this->headers["cc"];
		}
		$cc = array_merge($current, $addresses);
		$cc = array_filter(array_unique($cc));
		$this->setHeaders(["cc" => $cc]);
		return $this;
	}
	/**
	 * method to set the BCC header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setBcc(array $addresses, $append = false){
		$current = array();
		if($append){
			$current = (array)$this->headers["bcc"];
		}
		$bcc = array_merge($current, $addresses);
		$bcc = array_filter(array_unique($bcc));
		$this->setHeaders(["bcc" => $bcc]);
		return $this;
	}
	/**
	 * method to set the RETURN-PATH header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setReturnPath($path){
		$this->setHeaders(["return-path" => $path]);
		return $this;
	}
	/**
	 * method to set the SUBJECT header
	 * @param array $addresses The header value
	 * @param bool $append Toggle replace/append
	 * @return $this
	 */
	function setSubject($subject){
		$this->setHeaders(["subject" => $subject]);
		return $this;
	}
}