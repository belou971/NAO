<?php
// src/TS/NaoBundle/Form/RequestUpgradeType.php

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class RequestUpgradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $message = 'Le fichier est trop volumineux. Le poids maximum autorisé est de 2 Mo.';

        $builder->add('grade', FileType::class, array(
                'label' => 'Document (.pdf)',
                'constraints' => array(
                    new File(array(
                        'mimeTypes' => 'application/pdf',
                        'mimeTypesMessage' => 'Le fichier doit être au format PDF',
                        'maxSize' => '2M',
                        'maxSizeMessage' => $message,
                        'uploadIniSizeErrorMessage' => $message,
                        'notFoundMessage' => 'Le fichier n\'a pas été trouvé.',
                        'notReadableMessage' => 'Le fichier n\'a pas pu être chargé.',
                        'uploadFormSizeErrorMessage' => $message)),
                    new NotBlank(array(
                        'message' => 'Aucun fichier n\'a été sélectionné.'))),
                'required' => false));
    }
}
