<?php

namespace App\Form;

use App\Entity\PostType;
use App\Entity\Taxonomy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,TextType,HiddenType,TextareaType,NumberType };

class TaxonomyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_taxonomy', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'name_taxonomy'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('slug_taxonomy', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Slug",
                'attr'=> ['class'=>'regular-text','id'=>'slug_taxonomy'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('description_taxonomy', TextareaType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'label'=>"Description",
                'attr'=> ['class'=>'regular-text text-riche-convert','id'=>'description_template'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('parent_taxonomy',HiddenType::class, [
                'required' => false,
            ])
            ->add('autre_taxonomy',HiddenType::class, [
                'required' => false,
            ])
            ->add('OrderTaxonomy',NumberType::class,[
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'label'=>"Order",
                'attr'=> ['class'=>'regular-text','id'=>'order_taxonomy'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('StatutSideBar',CheckboxType::class,[
                'label'=>"In Sidebar",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('statutMenu',CheckboxType::class,[
                'label'=>"In MeFnu ront",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('is_draft',CheckboxType::class,[
                'label'=>"Is draft",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('Posttype', EntityType::class, [
                'label'=>"Post Type",
                // looks for choices from this entity
                'class' => PostType::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name_post_type',
                'attr'=>['class'=>'selectpicker regular-text'],
                // used to render a select box, check boxes or radios
                'multiple' => false,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Taxonomy::class,
        ]);
    }
}
