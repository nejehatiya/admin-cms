<?php

namespace App\Form;

use App\Entity\ModelesPost;
use App\Entity\PostType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,TextType};
class PostTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_post_type', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'name_post_type'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('slug_post_type', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"slug",
                'attr'=> ['class'=>'regular-text','id'=>'slug_post_type'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('icone_dasbord', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'label'=>"icone dasbord",
                'attr'=> ['class'=>'regular-text','id'=>'icone_dasbord'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('is_draft',CheckboxType::class,[
                'label'=>"Is Draft",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('displayInSitemap',CheckboxType::class,[
                'label'=>"Afficher dans le sitemap",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('has_list',CheckboxType::class,[
                'label'=>"Besoin d'une liste de pages",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('slug_in_url',CheckboxType::class,[
                'label'=>"Utiliser un slug dans l'URL",
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
            'data_class' => PostType::class,
        ]);
    }
}
