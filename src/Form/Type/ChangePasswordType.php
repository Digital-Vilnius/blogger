<?php

namespace App\Form\Type;

use App\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('password_fields_must_match'),
                'options' => array('attr' => array('class' => 'password-field form-control')),
                'required' => true,
                'first_options' => array('label' => $this->translator->trans('new_password')),
                'second_options' => array('label' => $this->translator->trans('repeat_new_password')),
            ))
            ->add('currentPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('password_fields_must_match'),
                'options' => array('attr' => array('class' => 'password-field form-control')),
                'required' => true,
                'first_options' => array('label' => $this->translator->trans('current_password')),
                'second_options' => array('label' => $this->translator->trans('repeat_current_password')),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
        ]);
    }
}