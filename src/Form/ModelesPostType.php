<?php

namespace App\Form;

use App\Entity\PostType;
use App\Entity\ModelesPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ModelesPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_modele', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'name_modele'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('status_modele',CheckboxType::class,[
                'label'=>"Statut",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('image_preview',HiddenType::class, [
                'attr' => ['class' => 'upload-image'],
            ])
            ->add('used_in', EntityType::class, [
                'label'=>"Post Type",
                // looks for choices from this entity
                'class' => PostType::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'post type',
                'attr'=>['class'=>'selectpicker'],
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('fields', TextareaType::class, [
                'attr' => ['class' => 'd-none'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModelesPost::class,
        ]);
    }
}
