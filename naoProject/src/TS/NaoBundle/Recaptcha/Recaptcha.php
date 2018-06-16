<?php
// src/TS/NaoBundle/Recaptcha/Recaptcha.php

namespace TS\NaoBundle\Recaptcha;

class Recaptcha
{
	private $secret;
	private $url;

	public function __construct($secret, $url)
	{
		$this->secret = $secret;
		$this->url = $url;
	}

	public function verify($code)
	{
		$response = file_get_contents($this->url . '?secret=' . $this->secret . "&response=" . $code);
		$json = json_decode($response, true);

		if($json['success'] == false) {
			return false;
		}

		return true;
	}
}