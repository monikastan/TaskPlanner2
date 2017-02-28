<?php

namespace TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchTaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => false))
                ->add('desc', 'text', array('required' => false))
                ->add('dateFrom', DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'js-datepicker',
                        'size' => '8',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                        )
                    ))
                ->add('dateTo', DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'js-datepicker',
                        'size' => '8',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                        )
                    ))
                ->add('deadlineFrom',  DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'js-datepicker',
                        'size' => '8',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                        )
                    ))
                ->add('deadlineTo',  DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'attr' => array(
                        'class' => 'js-datepicker',
                        'size' => '8',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                        )
                    ))
                ->add('priority', 'entity', array(
                    'required' => false,
                    'class' => 'TaskBundle:Priority',
                    'choice_label' => 'name'
                ))
                ->add('status', 'entity', array(
                    'required' => false,
                    'class' => 'TaskBundle:Status',
                    'choice_label' => 'name'
                ))
                ->add('category', 'entity', array(
                    'required' => false,
                    'class' => 'TaskBundle:Category',
                    'choice_label' => 'name'
                ));
    }
    
    /**
     * {@inheritdoc}
     */
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'TaskBundle\Entity\Task'
//        ));
//    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'taskbundle_task';
    }


}
