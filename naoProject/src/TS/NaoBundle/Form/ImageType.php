<?php

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TS\NaoBundle\Component\FileUploader;

class ImageType extends AbstractType
{
    private $uploader;
    /**
     * ImageType constructor.
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, array('label' => "Sélectionner une image"))
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
                    $imageData = $event->getData();
                    if(!is_null($imageData)) {
                        $imageData->setTargetDirectory($this->uploader->getTargetDirectory());
                    }
                });
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TS\NaoBundle\Entity\Image'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ts_naobundle_image';
    }


}
