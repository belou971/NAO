<?php
// src/TS/NaoBundle/Controller/AccountController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TS\NaoBundle\Entity\User;

class AccountController extends Controller
{
	public function dashboardAction(Request $request)
	{
		$user = $this->getUser();

		if (!$user->getActive()) {

			$this->get('security.token_storage')->setToken(null);
			$request->getSession()->invalidate();
			
			return $this->render('@TSNao/Account/active_account.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getConfirmToken()));
		}
		return $this->render('@TSNao/User/dashboard.html.twig');
	}
}