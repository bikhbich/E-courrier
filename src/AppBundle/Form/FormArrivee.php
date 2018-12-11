<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\OptionsResolver\OptionsResolver;



class FormArrivee extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('RefArrivee', TextType::class)
            ->add('RefCourrier', TextType::class)
            ->add('Type', TextType::class)
            ->add('Objet', TextareaType::class)
            ->add('DateCourrier', DateTimeType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd','label' => false,'html5' => false, ))
            ->add('DateArrivee', DateTimeType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd','label' => false,'html5' => false, ))
            ->add('DateCreation', DateTimeType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd' ,'data' => new \DateTime("now"),'label' => false,'html5' => false, ))
            ->add('Fichier', FileType::class, array('label' => 'Fichier (PDF)'))

           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Arrivee',

        ]);
    }
}
