<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\Menu;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class EmplacementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('key_emplacement', TextType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
                'label'=>"Nom",
                'attr'=> ['class'=>'regular-text','id'=>'key_emplacement'],
                // unmapped fields can't define their validation using annotations
            ])
            ->add('status',CheckboxType::class,[
                'label'=>"Statut",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            ->add('menu', EntityType::class, [
                'placeholder' => 'Select menu',
                'label'=>"Menu",
                // looks for choices from this entity
                'class' => Menu::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name_menu',
                'attr'=>['class'=>'selectpicker regular-text'],
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emplacement::class,
        ]);
    }
}
