<?php
// src/TS/NaoBundle/Account/Account.php

namespace TS\NaoBundle\Account;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TS\NaoBundle\PasswordRecovery\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\FileSystem\Exception\IOException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Enum\ProfilEnum;
use TS\NaoBundle\Email\Mailing;

class Account
{
	private $mailer;
	private $currentUser;
	private $flashMessage;
	private $targetDirectory;
	private $anonymousEmail;
	private $em;
	private $encoder;

	public function __construct(Mailing $mailer, TokenStorageInterface $tokenStorage, RequestStack $requestStack, $targetDirectory, $anonymousEmail, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$this->mailer = $mailer;
		$this->currentUser = $tokenStorage->getToken()->getUser();
		$this->flashMessage = $requestStack->getCurrentRequest()->getSession()->getFlashBag();
		$this->targetDirectory = $targetDirectory;
		$this->anonymousEmail = $anonymousEmail;
		$this->em = $em;
		$this->encoder = $encoder;
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

		if ($user == null || $user->getConfirmToken() != $token) {
			$this->flashMessage->add('error', 'Impossible de réinitialiser le mot de passe. L\'utilisateur n\'est pas reconnu.');
			return;
		}

		return $user;
	}

	public function activate($email, $token)
	{
		$user = $this->verify($email, $token);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible d\'activer votre compte. L\'identifiant est inconnu.');
			return false;
		}

		$user->setActive(true);
		$user->setConfirmToken(null);
		$this->em->flush();
		$this->flashMessage->add('success', 'Votre compte a bien été activé.');

		return true;
	}

	public function recovery($email)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de réinitialiser votre mot de passe. L\'identifiant est inconnu.');
			return;
		}

		$user->setConfirmToken(bin2hex(random_bytes(16)));
		$this->em->flush();
		$this->mailer->recoveryPassword($user);
		$this->flashMessage->add('success', 'Le mail de récupération de mot de passe a bien été envoyé.');
	}

	public function resetPassword($email, $password)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de mettre à jour le mot de passe. L\'utilisateur n\'a pas été reconnu.');
			return;
		}

		$this->encodePassword($user, $password);
		$user->setConfirmToken(null);
		$this->em->flush();
		$this->mailer->confirmEditPassword($user);
		$this->flashMessage->add('success', 'Mot de passe mis à jour.');
	}

	public function sendBackConfirmRegistration($email)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible d\'envoyer le mail de confirmation d\'inscription. L\'utilisateur n\'a pas été reconnu.');
			return;
		}

		$this->mailer->confirmRegistration($user);
		$this->flashMessage->add('success', 'Votre demande de renvoi d\'e-mail a bien été prise en compte.');
	}

	public function edit($email, $form)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de mettre à jour vos informations.');
			return;
		}

		if (!$this->encoder->isPasswordValid($user, $form->get('current_password')->getData())) {
			$this->flashMessage->add('error', 'Votre mot de passe actuel est incorrect.');
			return;
		}

		$username = $form->get('username')->getData();
		$email = $form->get('email')->getData();
		$password = $form->get('password')->getData();

		if ($username) {
			$user->setUsername($username);
		}

		if ($email) {
			$existingUser = $this->getUser($email);

			if ($existingUser instanceof User) {
				$this->flashMessage->add('error', 'Cette adresse e-mail est déjà prise.');
				return;
			}

			$user->setEmail($email);
		}

		if ($password) {
			$this->encodePassword($user, $password);
		}

		$this->em->flush();
		$this->flashMessage->add('success', 'Mise à jour effectuée.');
	}

	public function saveGrade(File $file)
	{
		$fileName = md5(uniqid()) . '.' . $file->guessExtension();
		
		try{
			if (!$this->currentUser instanceof User) {
				$this->flashMessage->add('error', 'Impossible d\'enregistrer le fichier envoyé.');
				return;
			}

			$file->move($this->targetDirectory . $this->currentUser->getEmail() . '/', $fileName );
		} 
		catch(FileException $e) {
			$this->flashMessage->add('error', 'Impossible d\'enregistrer le fichier envoyé.');
			return;
		}
		
		$this->currentUser->setGrade($this->currentUser->getEmail() . '/' . $fileName);
		$this->em->flush();
		$this->flashMessage->add('success', 'Votre demande d\'amélioration de compte a bien été envoyé. Vous recevrez la réponse par e-mail.');
	}

	public function upgrade($email, $status)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible d\'effectuer votre demande. L\'utilisateur n\'a pas été reconnu.');
			return;
		}

		$this->deleteGrade($user);
		
		if ($user->getGrade() != null) {
			return;
		}

		elseif ($status == "0") {

			$this->em->flush();
			$this->flashMessage->add('success', 'La demande de ' . $user->getName() . ' ' . $user->getSurname() . ' a bien été rejetée.');
			return;
		}
		
		$user->setRoles(ProfilEnum::NATURALIST);
		$this->em->flush();
		$this->flashMessage->add('success', $user->getName() . ' ' . $user->getSurname() . ' est désormais reconnu(e) comme Naturaliste.');
	}

	public function deleteGrade(User $user)
	{
		$files = new FileSystem();

		try {
			$files->remove($this->targetDirectory . $user->getGrade());
			$files->remove($this->targetDirectory . $user->getEmail());
		}
		catch (IOException $e) {
			$this->flashMessage->add('error', 'Impossible de supprimer le document de ' . $user->getName() . ' ' . $user->getSurname());
			return;
		}

		$user->setGrade(null);
	}

	public function isSameUser($email, $name, $surname)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			return false;
		}

		elseif($name != $user->getName() || $surname != $user->getSurname()) {
			return false;
		}

		return true;
	}

	public function encodePassword($user, $password)
	{
		$encoded = $this->encoder->encodePassword($user, $password);
		$user->setPassword($encoded);

		return $user;
	}

	public function switchUsers(User $user)
	{
		$anonymousAccount = $this->getUser($this->anonymousEmail);
		if($anonymousAccount == null) {
			$this->flashMessage->add('error', 'Impossible de supprimer votre compte pour le moment.');
			return false;
		}

		$observations = $user->getObservations();

		foreach($observations->getIterator() as $oneObs) {

			$anonymousAccount->addObservation($oneObs);
			$user->removeObservation($oneObs);
		}

		$this->deleteGrade($user);
		return true;
	}
}