<?php
// src/TS/NaoBundle/Account/Account.php

namespace TS\NaoBundle\Account;

use TS\NaoBundle\PasswordRecovery\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\FileSystem\Exception\IOException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Enum\ProfilEnum;
use TS\NaoBundle\Email\Mailing;

class Account
{
	private $mailer;
	private $currentUser;
	private $targetDirectory;
	private $em;

	public function __construct(Mailing $mailer, TokenStorageInterface $tokenStorage, $targetDirectory, EntityManagerInterface $em)
	{
		$this->mailer = $mailer;
		$this->currentUser = $tokenStorage->getToken()->getUser();
		$this->targetDirectory = $targetDirectory;
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

	public function saveGrade(File $file)
	{
		$fileName = md5(uniqid()) . '.' . $file->guessExtension();
		
		try{
			$file->move($this->targetDirectory . $this->currentUser->getEmail() . '/', $fileName );
		} 
		catch(FileException $e) {
			$message = $e->getMessage();

			return $message;
		}
		
		$this->currentUser->setGrade($this->currentUser->getEmail() . '/' . $fileName);
		$this->em->flush();
	}

	public function upgrade($email, $status)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			return;
		}

		$result = $this->deleteGrade($user);
		
		if (!empty($result)) {
			return $result;
		}

		elseif ($status == "0") {

			$this->em->flush();
			return 'La demande de ' . $user->getName() . ' ' . $user->getSurname() . ' a bien été rejetée.';
		}
		
		$user->setRoles(ProfilEnum::NATURALIST);
		$this->em->flush();
		return $user->getName() . ' ' . $user->getSurname() . ' est désormais reconnu(e) comme Naturaliste.';
	}

	public function deleteGrade(User $user)
	{
		$files = new FileSystem();

		try {
			$files->remove($this->targetDirectory . $user->getGrade());
			$files->remove($this->targetDirectory . $user->getEmail());
		}
		catch (IOException $e) {
			$message = 'Impossible de supprimer le document de ' . $user->getName() . ' ' . $user->getSurname();
			
			return $message;
		}

		$user->setGrade(null);
	}
}