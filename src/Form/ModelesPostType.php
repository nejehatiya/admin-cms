<?php

namespace App\Form;

use App\Entity\ModelesPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelesPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_modele')
            ->add('content_modele')
            ->add('shortcode_modele')
            ->add('path_modele')
            ->add('variable_modele')
            ->add('status_modele')
            ->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('date_upd', null, [
                'widget' => 'single_text',
            ])
            ->add('image_preview')
            ->add('used_in')
            ->add('fields')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModelesPost::class,
        ]);
    }
}
