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
	private $templating;

	public function __construct(\Swift_Mailer $mailer, $mailerUser, \Twig_Environment $templating)
	{
		$this->mailer = $mailer;
		$this->mailerUser = $mailerUser;
		$this->templating = $templating;
	}

	public function sendEmail(User $user)
	{
		$message = new \Swift_Message('Confirmation d\'inscription');
		$message->setBody($this->templating->render('@TSNao/Email/confirm_registration.html.twig'), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}