<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class)
            ->add('Email', EmailType::class)
            ->add('Role', ChoiceType::class, array('choices' => array('Directeur' =>'ROLE_DIRECTEUR', 'Chef division' => 'ROLE_DIVISION' , 'Chef service' => 'ROLE_SERVICE', "Bureau d'ordre" => 'ROLE_BUREAU_ORDRE', 'Agent' => 'ROLE_AGENT'), 'multiple'=> false))
            ->add('Division', ChoiceType::class, array('choices' => array('DAF' =>'DAF', 'DEP' => 'DEP' , 'DPP' => 'DPP'), 'multiple'=> false))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation de mot de passe'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }
}

