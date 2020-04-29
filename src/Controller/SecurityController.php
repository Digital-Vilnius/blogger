<?php

namespace App\Controller;

use App\Form\Type\ChangePasswordType;
use App\Model\ChangePassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    protected $entityManager;
    protected $translator;
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('shared/pages/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/change-password", name="change password")
     */
    public function changePassword(Request $request)
    {
        $changePassword = new ChangePassword();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $this->encoder->encodePassword($user, $changePassword->getNewPassword());
            $user->setPassword($encodedPassword);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('password_is_successfully_changed'));
            return $this->redirectToRoute('profile');
        }

        return $this->render('user/pages/change-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

}