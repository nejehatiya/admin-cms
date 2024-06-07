<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\TemplateMenu;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_menu')
            ->add('menu_content')
            ->add('status_menu')
            ->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('date_update', null, [
                'widget' => 'single_text',
            ])
            ->add('templateMenu', EntityType::class, [
                'class' => TemplateMenu::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
