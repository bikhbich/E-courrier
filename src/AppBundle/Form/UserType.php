<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Profils;
use AppBundle\Entity\Departements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Civilite', ChoiceType::class, array('choices' => array('Mr' =>'Mr', 'Mme' => 'Mme' , 'Mlle' => 'Mlle'), 'multiple'=> false))
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)
            ->add('Email', EmailType::class)
            ->add('Tel', TextType::class)
            ->add('Adresse', TextType::class)
            ->add('DateNaissance', DateType::class, array('widget' => 'single_text','format' => 'yyyy-MM-dd' ,'data' => new \DateTime("now"),'label' => false,'html5' => false, ))
            ->add('Role', ChoiceType::class, array('choices' => array('Directeur' =>'ROLE_DIRECTEUR', 'Chef division' => 'ROLE_DIVISION' , 'Chef service' => 'ROLE_SERVICE', "Bureau d'ordre" => 'ROLE_BUREAU_ORDRE', 'Agent' => 'ROLE_AGENT'), 'multiple'=> false))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation de mot de passe'],
            ])
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',

        ]);
    }
}

