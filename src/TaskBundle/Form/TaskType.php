<?php

namespace TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('description')
                //->add('deadline')
                ->add('deadline',  DateType::class, array(
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
                ->add('attach', FileType::class, array('label' => 'Attachement', 'required' => false
                        ))
                ->add('priority', 'entity', array(
                    'class' => 'TaskBundle:Priority',
                    'choice_label' => 'name'
                ))
                ->add('status', 'entity', array(
                    'class' => 'TaskBundle:Status',
                    'choice_label' => 'name'
                ))
                ->add('category', 'entity', array(
                    'class' => 'TaskBundle:Category',
                    'choice_label' => 'name'
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TaskBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'taskbundle_task';
    }


}
