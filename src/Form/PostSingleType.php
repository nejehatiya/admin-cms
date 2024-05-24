<?php

namespace App\Form;

use App\Entity\Images;
use App\Entity\Post;
use App\Entity\PostType;
use App\Entity\Terms;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostSingleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('post_title')
            ->add('post_content')
            ->add('post_excerpt')
            ->add('post_name')
            ->add('post_parent')
            ->add('guide')
            ->add('menu_ordre')
            ->add('post_status')
            ->add('comment_status')
            ->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('date_upd', null, [
                'widget' => 'single_text',
            ])
            ->add('post_content_2')
            ->add('post_order_content')
            ->add('post_parent_migration')
            ->add('post_id__migration')
            ->add('page_template')
            ->add('post_content_3')
            ->add('post_content_4')
            ->add('post_content_5')
            ->add('post_order_content_preinsertion')
            ->add('is_draft')
            ->add('page_menu')
            ->add('sommaire')
            ->add('is_index')
            ->add('is_follow')
            ->add('images', EntityType::class, [
                'class' => Images::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('terms', EntityType::class, [
                'class' => Terms::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('id_feature_image', EntityType::class, [
                'class' => Images::class,
                'choice_label' => 'id',
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('post_type', EntityType::class, [
                'class' => self::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
