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
			return $this->redirectToRoute('ts_nao_disabled');
		}

		return $this->render('@TSNao/User/dashboard.html.twig');
	}

	public function activeAction(Request $request)
	{
		if ($request->query->get('email') && $request->query->get('identifier')) {

			$accountService = $this->get('naobundle.account.account');
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');

			if ($accountService->activate($email, $token)) {

				return $this->redirectToRoute('ts_nao_dashboard');
			}
		}

		return $this->redirectToRoute('ts_nao_homepage');
	}

	public function disabledAction(Request $request)
	{
		$user = $this->getUser();
		$form = $this->get('form.factory')->create();
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$accountService = $this->get('naobundle.account.account');
			$accountService->sendBackConfirmRegistration($request->request->get('email'));

			return $this->redirectToRoute('ts_nao_homepage');
		}

		if (!$user instanceof User || $user->getActive()) {
			return $this->redirectToRoute('ts_nao_login');
		}

		$this->get('security.token_storage')->setToken(null);
		$request->getSession()->invalidate();

		return $this->render('@TSNao/Account/disabled_account.html.twig', array('email' => $user->getEmail(), 'form' => $form->createView()));
	}
}