<?php

namespace TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
                ->add('attach', FileType::class, array('label' => 'Attachement', 'required' => false, 'data_class' => null
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
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('c')
                            ->where('c.user = :userId')
                            ->setParameter('userId', $options['attr']['userId']);
    },
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
