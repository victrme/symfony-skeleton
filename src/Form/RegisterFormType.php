<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @extends AbstractType<User>
 */
class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'register.label.email',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'register.label.terms',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'register.err.terms_not_agreed',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'register.label.password',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'register.err.pw_not_blank',
                    ),
                    new Length(
                        min: 6,
                        max: 4096,
                        minMessage: 'register.err.pw_too_short',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'auth',
        ]);
    }
}
