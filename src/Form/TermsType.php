<?php

namespace App\Form;

use App\Entity\Images;
use App\Entity\Post;
use App\Entity\Taxonomy;
use App\Entity\Terms;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,TextType,HiddenType,TextareaType,NumberType };
class TermsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Access the custom option
        $taxonomy = array_key_exists('taxonomy',$options)?$options['taxonomy']:'not_found';
        $builder
            ->add('name_terms', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'name_terms'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('slug_terms', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Slug",
                'attr'=> ['class'=>'regular-text','id'=>'slug_terms'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('description_terms', TextareaType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'label'=>"Description",
                'attr'=> ['class'=>'regular-text text-riche-convert','id'=>'description_template'],
                // unmapped fields can't define their validation using annotations
            ])
            
            ->add('level',HiddenType::class, [
                'attr' => ['class' => ''],
            ])
            ->add('is_draft',CheckboxType::class,[
                'label'=>"Statut",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('id_taxonomy',HiddenType::class, [
                'attr' => ['class' => ''],
            ])
            ->add('parentTerms', EntityType::class, [
                'placeholder' => 'Parent',
                'class' => Terms::class,
                'query_builder' => function (EntityRepository $er)use($taxonomy) : QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->join('u.id_taxonomy ','t')
                        ->andWhere('t.slug_taxonomy = :taxonomy')
                        ->setParameter('taxonomy', $taxonomy)
                        ->andWhere('u.parentTerms = :parent_terms')
                        ->setParameter('parent_terms', 0)
                        ->orderBy('u.parentTerms', 'ASC');
                },
                'label'=>"Parent",
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name_terms',
                'attr'=>['class'=>'selectpicker regular-text'],
                // used to render a select box, check boxes or radios
                'multiple' => false,
            ])
            
            ->add('image',HiddenType::class, [
                'attr' => ['class' => 'upload-image'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terms::class,
            'taxonomy' => null,
        ]);
        $resolver->setAllowedTypes('taxonomy', ['null', 'string']);
    }
}
