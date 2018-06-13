<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 20/10/17
 * Time: 16:55
 */

namespace TS\NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'widget' => 'single_text',
                'label' => false,
                'format' => 'dd-MM-yyyy HH:mm',
                'attr' => array('class' => 'datetime-picker input-group')
            )
        );
    }

    public function getParent()
    {
        return DateTimeType::class;
    }

    public function getBlockPrefix()
    {
        return 'datetimepicker';
    }
}