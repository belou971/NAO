<?php

namespace TS\NaoBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationType extends AbstractType
{
    private $em;

    /**
     * AvailableDateType constructor.
     * @param $entityManager
     */
    public function __construct( EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array('required' => true,
                                                                'attr' => array('placeholder' => 'Entrer un titre pour cette observation')))
            ->add('specimen', TextType::class, array('required' => true,
                                                               'attr' => array('placeholder' => "Entrer le nom de l'espèce")))
            ->add('nbSpecimen', IntegerType::class, array('attr' => array('min'=> 0),
                                                                    'data'=> 0))
            ->add('dtCreation', DateTimePickerType::class, array('required' => true))
            ->add('longitude', NumberType::class, array('scale' => 6,
                                                                  'required' => true,
                                                                   'attr' => array('placeholder' => 1.234567)))
            ->add('latitude', NumberType::class, array('scale' => 6,
                                                                 'required' => true,
                                                                 'attr' => array('placeholder' => 1.234567)))
            ->add('remarks', TextareaType::class, array('required' => true,
                                                                  'attr' => array('placeholder' =>"Description détaillée de l'oiseau: silhouette, taille, coloration du plumage partie par partie, voix, comportement, ... ")))
            ->add('reset', ResetType::class, array('label' => 'Annuler'))
            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
                $obsData = $event->getData();
                $taxrefRepo = $this->em->getRepository('TSNaoBundle:TAXREF');

                if(!is_null($taxrefRepo)) {
                    $result = $taxrefRepo->findBySpecimen($obsData->getSpecimen());
                    if(count($result) === 1) {
                        $taxref = $result[0];
                        $obsData->setTaxref($taxref);
                    }
                }

                $event->setData($obsData);
            });;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TS\NaoBundle\Entity\Observation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ts_naobundle_observation';
    }


}
