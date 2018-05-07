<?php
// src/TS/NaoBundle/PasswordRecovery/PasswordRecovery.php

namespace TS\NaoBundle\PasswordRecovery;

use Doctrine\ORM\EntityManagerInterface;
use TS\NaoBundle\Entity\User;

class PasswordRecovery
{
	private $mailer;
	private $em;
	private $mailerUser;
	private $templating;
	
	public function __construct(\Swift_Mailer $mailer, EntityManagerInterface $em, $mailerUser, \Twig_Environment $templating)
	{
		$this->mailer = $mailer;
		$this->em = $em;
		$this->mailerUser = $mailerUser;
		$this->templating = $templating;
	}

	public function recovery($email)
	{
		$user = $this->em->getRepository('TSNaoBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User) {
			return;
		}

		$user->setRecoveryToken(bin2hex(random_bytes(16)));

		$this->em->flush($user);
		$this->sendEmail($user);
	}

	public function sendEmail(User $user)
	{
		$message = new \Swift_Message('Mot de passe oublié');
		$message->setBody($this->templating->render('@TSNao/Email/email_recovery.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getRecoveryToken())), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}