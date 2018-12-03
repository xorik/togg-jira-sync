<?php

namespace App\Form;

use App\Model\TogglTimeEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('description')
            ->add('start')
            ->add('dur')
        ;

        $builder->get('start')->addModelTransformer(new DateTimeToStringTransformer(null, null, DATE_ATOM));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TogglTimeEntry::class,
            'allow_extra_fields' => true,
        ]);
    }
}
