<?php

namespace SimpleMessage;

trait AttachmentTrait {
	/**
	 * store a list of filenames to attach
	 */
	protected $attachments;
	/**
	 * a finite list of supported file extensions with mime types
	 */
	protected $supportedTypes = array(
		"pdf"  => "application/pdf",
		"zip"  => "application/zip",
		"rtf"  => "text/rtf",
		"csv"  => "text/csv",
		"html" => "text/html",
		"txt"  => "text/plain",
		"jpeg" => "image/jpeg",
		"jpg"  => "image/jpeg",
		"png"  => "image/png",
		"gif"  => "image/gif",
	);
	/**
	 * method to get the mime type based on the file extension
	 * @param string $ext The file extension
	 * @return string
	 */
	function getMIMEType($ext){
		$ext = strtolower($ext);
		if(array_key_exists($ext, $this->supportedTypes)){
			return $this->supportedTypes[$ext];
		}
	}
	/**
	 * method to add a file to attach
	 * @param string $file The path/to/the/file.ext
	 * @return
	 */
	function attachFile($file){
		if(is_file($file)){
			$this->attachments[] = $file;
		}
	}
	/**
	 * method to order the list of attachments
	 * @param string $boundary The boundary string to use
	 * @return string
	 */
	protected function assembleAttachments($boundary){
		$attachments = "";

		if($this->attachments){

			$this->setHeaders(["content-type" => "multipart/mixed; boundary=\"{$boundary}\""]);

			foreach($this->attachments as $path){
				$pathinfo = pathinfo($path);

				$mimeType = $this->getMIMEType($pathinfo["extension"]);
				$basename = $pathinfo["basename"];

				$attachments .= "--" . $boundary . $this->delim;
				$attachments .= $this->formatHeader("content-type", $mimeType);
				$attachments .= $this->formatHeader("content-disposition", "attachment; filename=\"{$basename}\"");
				$attachments .= $this->formatHeader("content-transfer-encoding", "base64");
				$attachments .= $this->delim;
				$attachments .= sprintf("%s%s", chunk_split(base64_encode(file_get_contents($path))), $this->delim);
			}
		}

		$attachments .= "--" . $boundary . "--" . $this->delim;
		return $attachments;
	}
}