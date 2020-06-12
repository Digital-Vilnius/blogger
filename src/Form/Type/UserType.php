<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private $translator;
    private $encoder;

    public function __construct(TranslatorInterface $translator, UserPasswordEncoderInterface $encoder)
    {
        $this->translator = $translator;
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('email')
            ])
            ->add('roles', ChoiceType::class, [
                'label' => $this->translator->trans('roles'),
                'choices' => [
                    $this->translator->trans('admin') => 'ROLE_ADMIN',
                ],
                'multiple' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => $this->translator->trans('password')
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $user = $event->getForm()->getData();
            $data = $event->getData();
            $data['password'] = $this->encoder->encodePassword($user, $data['password']);
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}