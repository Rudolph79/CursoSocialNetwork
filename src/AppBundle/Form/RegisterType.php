<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('role')
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-name form-control']])
            ->add('surname', TextType::class, [
                'label' => 'Surname',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-surname form-control']])
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-email form-control']])
            ->add('nick', TextType::class, [
                'label' => 'Nickname',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-nick form-control nick-input']])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-password form-control']])
            ->add('Register', SubmitType::class, [
                'attr' => [
                    'class' => 'form-submit btn btn-success']
            ]
            );
            //->add('bio')
            //->add('active')
            //->add('image');
    }
    /**
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
