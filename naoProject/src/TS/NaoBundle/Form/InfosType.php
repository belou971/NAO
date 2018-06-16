<?php 

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
                    'required' => false,
                    'label' => 'Nom d\'affichage'))
                ->add('email', RepeatedType::class, array(
                    'type' => EmailType::class,
                    'required' => false,
                    'invalid_message' => 'L\'adresse e-mail doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouvelle adresse e-mail'),
                    'second_options' => array(
                        'label' => 'Confirmez votre nouvelle adresse e-mail',
                        'attr' => array(
                            'autocomplete' => 'off')),
                    'constraints' => array(
                        new Email(array(
                            'message' => 'Veuillez entrer une adresse e-mail correcte.',
                            'checkMX' => true)))))
                ->add('current_password', PasswordType::class, array(
                    'label' => 'Mot de passe actuel',
                    'constraints' => array(
                        new NotBlank(array(
                            'message' => 'Le mot de passe actuel doit être renseigné pour continuer.')))))
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'required' => false,
                    'invalid_message' => 'Le mot de passe doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouveau mot de passe'),
                    'second_options' => array(
                        'label' => 'Confirmez votre mot de passe'),
                    'constraints' => array(
                        new Regex(array(
                            'pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#', 'message' => 'Votre mot de passe doit faire au minimum 6 caractères et contenir au moins 1 lettre min, 1 lettre maj et 1 chiffre.')))));
    }
}
