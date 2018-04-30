<?php

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', RepeatedType::class, array(
                    'type' => EmailType::class,
                    'invalid_message' => 'L\'adresse e-mail doit être identique.',
                    'first_options' => array('label' => 'Adresse e-mail'),
                    'second_options' => array('label' => 'Confirmez votre adresse e-mail')))
                ->add('pwd', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe doit être identique.',
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Confirmez votre mot de passe')))
                ->add('cgu', CheckboxType::class, array(
                    'mapped' => false,
                    'required' => false,
                    'constraints' => array(new IsTrue(array('message' => 'Veuillez accepter les conditions générales d\'utilisation pour continuer.')))))
                ->add('S\'inscrire', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TS\NaoBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ts_naobundle_user';
    }


}
