<?php

namespace MyNetwork\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Firstname',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-name form-control'
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Lastname',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-surname form-control'
                ]
            ])
            ->add('nick', TextType::class, [
                'label' => 'Nickname',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-nick form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-email form-control'
                ]
            ])
            /*->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-password form-control'
                ]
            ])*/
            ->add('bio', TextareaType::class, [
                'label' => 'About you',
                'required' => false,
                'attr' => [
                    'class' => 'form-bio form-control'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-image form-control'
                ]
            ])
            ->add('Save', SubmitType::class, [
                "attr" => [
                    "class" => "form-submit btn btn-success"
                ]
            ])
            ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyNetwork\BackendBundle\Entity\Users'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mynetwork_backendbundle_users';
    }


}
