<?php

namespace App\Form\Type;

use App\Entity\Notification;
use App\Entity\Subscriber;
use App\Enum\Channels;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class, [
                'label' => $this->translator->trans('content'),
            ])
            ->add('htmlContent', TextType::class, [
                'label' => $this->translator->trans('html_content'),
            ])
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('title'),
            ])
            ->add('subscriber', EntityType::class, [
                'label' => $this->translator->trans('subscriber'),
                'class' => Subscriber::class
            ])
            ->add('channel', ChoiceType::class, [
                'label' => $this->translator->trans('channels'),
                'choices'  => [
                    $this->translator->trans('sms') => Channels::SMS,
                    $this->translator->trans('email') => Channels::EMAIL,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}