<?php

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
                    'label' => 'Nom *',
                    'constraints' => new Length(array(
                        'min' => 2,
                        'minMessage' => 'Le nom doit comporter au minimum 2 caractères.'))))
                ->add('surname', TextType::class, array(
                    'label' => 'Prénom *',
                    'constraints' => new Length(array(
                        'min' => 2,
                        'minMessage' => 'Le prénom doit comporter au minimum 2 caractères.'))))
                ->add('email', RepeatedType::class, array(
                    'type' => EmailType::class,
                    'invalid_message' => 'L\'adresse e-mail doit être identique.',
                    'first_options' => array('label' => 'Adresse e-mail *'),
                    'second_options' => array('label' => 'Confirmez votre adresse e-mail *')))
                ->add('message', TextareaType::class, array(
                    'label' => 'Message *'))
                ->add('Envoyer', SubmitType::class);
    }
}
