<?php

namespace App\Form;

use App\Entity\PostType;
use App\Entity\TemplatePage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,TextType};
class TemplatePageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'name_template'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('slug', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'slug_template'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('post_type', EntityType::class, [
                'label'=>"Post Type",
                // looks for choices from this entity
                'class' => PostType::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name_post_type',
                'attr'=>['class'=>'selectpicker regular-text'],
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('status',CheckboxType::class,[
                'label'=>"Statut",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplatePage::class,
        ]);
    }
}
