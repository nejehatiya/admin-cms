<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
;
use Symfony\Component\Form\Extension\Core\Type\{HiddenType,CheckboxType,PasswordType};
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            //->add('roles')
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('first_name')
            ->add('last_name')
            //->add('token_key')
            ->add('image_profil',HiddenType::class, [
                'attr' => ['class' => 'upload-image'],
            ])
            ->add('status',CheckboxType::class,[
                'label'=>"Statut",
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr'=> ['class'=>'form-check-input']
            ])
            /*->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('date_upd', null, [
                'widget' => 'single_text',
            ])
            ->add('authCode')*/
            //->add('isVerified')
            ->add('roles_user', EntityType::class, [
                'class' => Roles::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'role',
                'attr'=>['class'=>'selectpicker regular-text'],
                // used to render a select box, check boxes or radios
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
