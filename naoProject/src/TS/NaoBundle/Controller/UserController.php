<?php 
// src/TS/NaoBundle/Controller/UserController.php

namespace TS\NaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TS\NaoBundle\Entity\User;
use TS\NaoBundle\Form\UserType;

class UserController extends Controller
{
	public function registrationAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			
			$em = $this->getDoctrine()->getManager();
			return new Response('Bravo!');
		}
		return $this->render('@TSNao/User/registration.html.twig', array('form' => $form->createView()));
	}

	public function loginAction()
	{

	}

	public function deleteAccountAction()
	{
		
	}
}