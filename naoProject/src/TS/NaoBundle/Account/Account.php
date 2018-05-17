<?php
// src/TS/NaoBundle/Account/Account.php

namespace TS\NaoBundle\Account;

use TS\NaoBundle\PasswordRecovery\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Email\Mailing;

class Account
{
	private $mailer;
	private $em;

	public function __construct(Mailing $mailer, EntityManagerInterface $em)
	{
		$this->mailer = $mailer;
		$this->em = $em;
	}

	public function getUser($email)
	{
		$user = $this->em->getRepository('TSNaoBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User) {
			return;
		}

		return $user;
	}

	public function verify($email, $token)
	{
		$user = $this->getUser($email);

		if ($user != null && $user->getConfirmToken() == $token) {
			
			return $user;
		}
	}

	public function activate($email, $token)
	{
		$user = $this->verify($email, $token);

		if ($user != null) {

			$user->setActive(true);
			$user->setConfirmToken(null);
			$this->em->flush();

			return true;
		}

		return false;
	}

	public function recovery($email)
	{
		$user = $this->getUser($email);

		if ($user != null) {
			
			$user->setConfirmToken(bin2hex(random_bytes(16)));
			$this->em->flush();
			$this->mailer->recoveryPassword($user);
		}
	}

	public function resetPassword($email, $password)
	{
		$user = $this->getUser($email);

		if ($user != null) {
			
			$user->setPassword($password);
			$user->setConfirmToken(null);
			$this->em->flush();
			$this->mailer->confirmEditPassword($user);
		}
	}

	public function sendBackConfirmRegistration($email)
	{
		$user = $this->getUser($email);

		if ($user != null) {

			$this->mailer->confirmRegistration($user);
		}
	}
}