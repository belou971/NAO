<?php 
// src/TS/NaoBundle/Email/RegistrationConfirmation.php

Namespace TS\NaoBundle\Email;

use TS\NaoBundle\Entity\User;

class RegistrationConfirmation
{
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	private $mailerUser;

	public function __construct(\Swift_Mailer $mailer, $mailerUser)
	{
		$this->mailer = $mailer;
		$this->mailerUser = $mailerUser;
	}

	public function sendEmail(User $user)
	{
		$message = new \Swift_Message('Confirmation d\'inscription', 'Bienvenue !'); // à insérer ici template mail de bienvenue.
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}