<?php 
// src/TS/NaoBundle/Controller/UserController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Form\UserType;
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

			return new Response('Inscrit !');
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