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

		if (!$user instanceof User || $user == null) {
			return;
		}

		$user->setConfirmToken(bin2hex(random_bytes(16)));
		$this->em->flush();

		$this->sendEmailRecovery($user);
	}

	public function verify($email, $token)
	{
		$user = $this->em->getRepository('TSNaoBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User || $user == null) {
			return false;
		}
		elseif ($user->getConfirmToken() != $token) {
			return false;
		}
		else {
			return true;
		}
	}

	public function reset($email, $password)
	{
		$user = $this->em->getRepository('TSNaoBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User || $user == null) {
			return;
		}

		$user->setPassword($password);
		$user->setConfirmToken(null);
		$this->em->flush();

		$this->sendEmailConfirmation($user);
	}

	public function sendEmailRecovery(User $user)
	{
		$message = new \Swift_Message('Mot de passe oublié');
		$message->setBody($this->templating->render('@TSNao/Email/email_recovery.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getConfirmToken())), 'text/html');

		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function sendEmailConfirmation(User $user)
	{
		$message = new \Swift_Message('Mot de passe changé !');
		$message->setBody($this->templating->render('@TSNao/Email/confirm_pass.html.twig'), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}