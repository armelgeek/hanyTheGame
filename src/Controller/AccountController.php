<?php

namespace App\Controller;

use App\Form\UpdatePasswordType;
use App\Form\UserUpdateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Form\UserType;

class AccountController extends AbstractController
{
    /**
     * @Route("mg/hitady/admin/mon-compte", name="account")
     * @Method({"GET", "POST"})
     */

    public function account(UserPasswordEncoderInterface $passwordEncoder,
                            AuthorizationCheckerInterface $authChecker,
                            Request $request,
                            UserPasswordEncoderInterface $encoder)
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if (!$authChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $formUpdate = $this->createForm(UserUpdateType::class, $this->getUser(), [
            'validation_groups' => array('User'),
        ]);
        $formPassword = $this->createForm(UpdatePasswordType::class, $this->getUser(), [
            'validation_groups' => array('User'),
        ]);
        $action = $request->get('action');
        if ($action === 'update') {
            $formUpdate->handleRequest($request);
        } else if ($action === 'password') {
            $formPassword->handleRequest($request);
        }

        // Traitement du mot de passe
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $plainPassword = $user->getPlainPassword();

            if (null !== $plainPassword) {
                $encoded = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
            }
            $em->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }
        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $data = $formUpdate->getData();
            // TODO : Le teste ne marche pas,il faut verifier les etapes
            if ($user->getEmail() == $data->email) {
                $request->getSession()->getFlashBag()->add('success', 'Votre profil a bien été mis à jour');
            } else {
                $request->getSession()->getFlashBag()->add('success', "Votre profil a bien été mis à jour, un email a été envoyé à {$data->email} pour confirmer votre changement");

            }
            $em->flush();

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('account/index.html.twig', [
            'formPassword' => $formPassword->createView(),
            'formUpdate' => $formUpdate->createView(),
        ]);
    }
    /**
     * @Route("mg/hitady/admin/profil/avatar", name="user_avatar", methods={"POST"})
     */
    public function avatar(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ProfileService $service
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $data = new AvatarDto($request->files->get('avatar'), $user);
        $errors = $validator->validate($data);
        if ($errors->count() > 0) {
            $this->addFlash('error', (string)$errors->get(0)->getMessage());
        } else {
            $service->updateAvatar($data);
            $em->flush();
            $this->addFlash('success', 'Avatar mis à jour avec succès');
        }

        return $this->redirectToRoute('user_edit');
    }
}
