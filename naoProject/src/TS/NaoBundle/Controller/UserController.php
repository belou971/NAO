<?php 
// src/TS/NaoBundle/Controller/UserController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Form\UserType;
use TS\NaoBundle\Form\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
	public function registrationAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('ts_nao_login');
		}
		return $this->render('@TSNao/User/registration.html.twig', array('form' => $form->createView()));
	}

	public function loginAction()
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return $this->redirectToRoute('ts_nao_dashbord');
		}

		$authenticationUtils = $this->get('security.authentication_utils');

		return $this->render('@TSNao/User/login.html.twig', array(
			'last_username' => $authenticationUtils->getLastUsername(),
			'error' => $authenticationUtils->getLastAuthenticationError()));
	}

	public function recoveryAction(Request $request)
	{
		$submittedToken = $request->request->get('_csrf_token');

    	if($request->isMethod('POST') && $this->isCsrfTokenValid('authenticate', $submittedToken))
    	{
    		$email = $request->request->get('email');

    		$recoveryService = $this->get('naobunble.password_recovery.password_recovery');
    		$recoveryService->recovery($email);

    		return $this->redirectToRoute('ts_nao_homepage');
    	}

    	return $this->render('@TSNao/User/recovery.html.twig');
	}

	public function resetPasswordAction(Request $request)
	{
		$recoveryService = $this->get('naobunble.password_recovery.password_recovery');
		$form = $this->createForm(ResetPasswordType::class);
		if ($request->isMethod('POST')) {

			if ($form->handleRequest($request)->isValid()) {
				$recoveryService->reset($request->request->get('email'), $form->get('password')->getData());
				return $this->redirectToRoute('ts_nao_login');
			}
			return $this->render('@TSNao/User/reset_password.html.twig', array('form' => $form->createView(), 'email' => $request->request->get('email')));
		}
		if ($request->query->get('email') && $request->query->get('identifier')) {
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');
			if ($recoveryService->verify($email, $token)) {

				return $this->render('@TSNao/User/reset_password.html.twig', array('form' => $form->createView(), 'email' => $email));
			}
		}
		return $this->redirectToRoute('ts_nao_homepage');
	}

	/*
	 * @Security("has_role('ROLE_BIRD_FANCIER')")
	 */
	public function dashboardAction()
	{
		return $this->render('@TSNao/User/dashbord.html.twig');
	}

	public function deleteAccountAction()
	{
		
	}
}