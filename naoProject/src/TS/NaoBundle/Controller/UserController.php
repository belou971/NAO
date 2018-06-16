<?php 
// src/TS/NaoBundle/Controller/UserController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Form\UserType;
use TS\NaoBundle\Form\ContactType;
use TS\NaoBundle\Form\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
	public function registrationAction(Request $request)
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('ts_nao_dashboard');
		}
	
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', 'Compte crée avec succès.');

			return $this->redirectToRoute('ts_nao_login');
		}
		
		return $this->render('@TSNao/User/registration.html.twig', array('form' => $form->createView(), "modal" => false));
	}

	public function loginAction()
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('ts_nao_dashboard');
		}

		$authenticationUtils = $this->get('security.authentication_utils');

		return $this->render('@TSNao/User/login.html.twig', array(
            'modal' => false,
			'last_username' => $authenticationUtils->getLastUsername(),
			'error' => $authenticationUtils->getLastAuthenticationError()));
	}

	public function recoveryAction(Request $request)
	{
		$submittedToken = $request->request->get('_csrf_token');
    	if($request->isMethod('POST') && $this->isCsrfTokenValid('authenticate', $submittedToken))
    	{
    		$email = $request->request->get('email');
    		$accountService = $this->get('naobundle.account.account');
    		$accountService->recovery($email);

    		return $this->redirectToRoute('ts_nao_recovery');
    	}

    	return $this->render('@TSNao/User/recovery.html.twig', array("modal" => false));
	}

	public function resetPasswordAction(Request $request)
	{
		$accountService = $this->get('naobundle.account.account');
		$form = $this->createForm(ResetPasswordType::class);
		if ($request->isMethod('POST')) {

			if ($form->handleRequest($request)->isValid()) {
				$accountService->resetPassword($request->request->get('email'), $form->get('password')->getData());
				return $this->redirectToRoute('ts_nao_login');
			}
			return $this->render('@TSNao/User/reset_password.html.twig', array('form' => $form->createView(), 'email' => $request->request->get('email'), "modal" => false));
		}

		if ($request->query->get('email') && $request->query->get('identifier')) {
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');
			if ($accountService->verify($email, $token) != null) {

				return $this->render('@TSNao/User/reset_password.html.twig', array('form' => $form->createView(), 'email' => $email, "modal" => false));
			}
		}
		return $this->redirectToRoute('ts_nao_homepage');
	}

	/**
     * @Security("has_role('ROLE_BIRD_FANCIER')")
     */
	public function deleteAccountAction(Request $request)
	{
		$user = $this->getUser();

		if (!$user->getActive()) {
			return $this->redirectToRoute('ts_nao_disabled');
		}

		$form = $this->get('form.factory')->create();
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($user);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', 'Votre compte a bien été supprimé.');
			$this->get('security.token_storage')->setToken(null);

			return $this->redirectToRoute('ts_nao_homepage');
		}

		return $this->render('@TSNao/User/delete_account.html.twig', array('form' => $form->createView(), 'modal' => false));
	}

	public function contactAction(Request $request)
	{
		$form = $this->createForm(ContactType::class);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$request->getSession()->getFlashBag()->add('success', 'Message envoyé !');
		}

		return $this->render('@TSNao/User/contact.html.twig', array('form' => $form->createView(), 'modal' => false));
	}
}