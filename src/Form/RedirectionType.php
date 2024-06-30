<?php

namespace App\Form;

use App\Entity\Redirection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,TextType};

class RedirectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_root', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"URL Récent",
                'attr'=> ['class'=>'regular-text','id'=>'old_root'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('new_root', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nouveau URL",
                'attr'=> ['class'=>'regular-text','id'=>'new_root'],
                // unmapped fields can't define their validation using annotations
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Redirection::class,
        ]);
    }
}
