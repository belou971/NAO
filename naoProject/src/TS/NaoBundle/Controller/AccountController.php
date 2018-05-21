<?php
// src/TS/NaoBundle/Controller/AccountController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Form\RequestUpgradeType;

class AccountController extends Controller
{
	public function dashboardAction(Request $request)
	{
		$user = $this->getUser();
		if (!$user->getActive()) {
			return $this->redirectToRoute('ts_nao_disabled');
		}

		return $this->render('@TSNao/Account/dashboard.html.twig');
	}

	public function activeAction(Request $request)
	{
		if ($request->query->get('email') && $request->query->get('identifier')) {

			$accountService = $this->get('naobundle.account.account');
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');

			if ($accountService->activate($email, $token)) {
				return $this->redirectToRoute('ts_nao_login');
			}
		}

		return $this->redirectToRoute('ts_nao_homepage');
	}

	/**
     * @Security("has_role('ROLE_BIRD_FANCIER')")
     */
	public function disabledAction(Request $request)
	{
		$user = $this->getUser();
		$form = $this->get('form.factory')->create();

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$accountService = $this->get('naobundle.account.account');
			$accountService->sendBackConfirmRegistration($request->request->get('email'));

			return $this->redirectToRoute('ts_nao_homepage');
		}

		if ($user->getActive()) {
			return $this->redirectToRoute('ts_nao_dashboard');
		}

		$this->get('security.token_storage')->setToken(null);
		$request->getSession()->invalidate();

		return $this->render('@TSNao/Account/disabled_account.html.twig', array('email' => $user->getEmail(), 'form' => $form->createView()));
	}

	public function requestUpgradeAction(Request $request)
	{
		$form = $this->createForm(RequestUpgradeType::class);
		if ($request->isMethod('POST') && $form->handleRequest($request) && $form->isValid()) {
			
			$accountService = $this->get('naobundle.account.account');
			$result = $accountService->saveGrade($form->get('grade')->getData());

			if ($result != null) {
				return new Response($result);
			}

			return $this->redirectToRoute('ts_nao_dashboard');
		}

		return $this->render('@TSNao/Account/request_upgrade.html.twig', array('form' => $form->createView()));
	}

	/*
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function upgradeRequestListAction(Request $request)
	{
		$requestList = $this->getDoctrine()->getManager()->getRepository('TSNaoBundle:User')->getUpgradeRequestList();
		
		return $this->render('@TSNao/Account/upgrade_request_list.html.twig', array('requestList' => $requestList));
	}
}