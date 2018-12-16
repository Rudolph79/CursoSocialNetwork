<?php

namespace MyNetwork\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Message',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Picture',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' =>  'form-control'
                ]
            ])
            ->add('document', FileType::class, [
                'label' => 'Document',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' =>  'form-control'
                ]
            ])
            /*->add('image')
            ->add('status')
            ->add('createdAt')
            ->add('user')*/
            ->add('Submit', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-success"
                ]
            ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyNetwork\BackendBundle\Entity\Publications'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mynetwork_backendbundle_publications';
    }


}
