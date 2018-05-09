<?php

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe doit être identique.',
                    'first_options' => array('label' => 'Nouveau mot de passe *'),
                    'second_options' => array('label' => 'Confirmez votre mot de passe *'),
                    'constraints' => array(new Regex(array('pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#', 'message' => 'Votre mot de passe doit faire au minimum 6 caractères et contenir au moins 1 lettre min, 1 lettre maj et 1 chiffre.')))))
                ->add('Reinitialiser', SubmitType::class);
    }
}
