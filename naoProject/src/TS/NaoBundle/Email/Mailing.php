<?php 
// src/TS/NaoBundle/Email/Mailing.php

Namespace TS\NaoBundle\Email;

use TS\NaoBundle\Entity\User;

class Mailing
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

	public function confirmRegistration(User $user)
	{
		$message = new \Swift_Message('Confirmation d\'inscription');
		$message->setBody($this->templating->render('@TSNao/Email/confirm_registration.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getConfirmToken())), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function recoveryPassword(User $user)
	{
		$message = new \Swift_Message('Mot de passe oublié ?');
		$message->setBody($this->templating->render('@TSNao/Email/email_recovery.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getConfirmToken())), 'text/html');

		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function confirmEditPassword(User $user)
	{
		$message = new \Swift_Message('Mot de passe mis à jour !');
		$message->setBody($this->templating->render('@TSNao/Email/confirm_pass.html.twig'), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function contactMail($form)
	{
		$name = $form->get('name')->getData();
		$surname = $form->get('surname')->getData();
		$email = $form->get('email')->getData();
		$contact_message = $form->get('message')->getData();

		$message = new \Swift_Message('Demande d\'informations');
		$message->setBody($this->templating->render('@TSNao/Email/contact.html.twig', array('name' => $name, 'surname' => $surname, 'email' => $email, 'message' => $contact_message)), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($this->mailerUser);
		$this->mailer->send($message);
	}

	public function deleteAccount(User $user)
	{
		$message = new \Swift_Message('Suppression de compte');
		$message->setBody($this->templating->render('@TSNao/Email/confirm_delete_account.html.twig'), 'text/html');
		$message->setFrom([$this->mailerUser => 'Nos Amis les Oiseaux'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}