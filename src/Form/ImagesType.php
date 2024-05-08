<?php

namespace App\Form;

use App\Entity\Images;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url_image')
            ->add('name_image')
            ->add('description_image')
            ->add('alt_image')
            ->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('date_update', null, [
                'widget' => 'single_text',
            ])
            ->add('height')
            ->add('width')
            ->add('id_post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
