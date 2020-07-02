<?php

namespace App\Controller;

use App\Services\ProfileService;
use App\Entity\EmailVerification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailChangeController extends AbstractController
{

    /**
     * @Route("/email-confirm/{token}", name="user_email_confirm")
     */
    public function confirm(
        EmailVerification $emailVerification,
        ProfileService $service,
        EntityManagerInterface $em
    ): Response {
        if ($emailVerification->isExpired()) {
            $this->addFlash('error', 'Cette demande de confirmation a expirée');
        } else {
            $service->updateEmail($emailVerification, $em);
            $em->flush();
            $this->addFlash('success', 'Votre email a bien été modifié');
        }
        return $this->redirectToRoute('user_edit');
    }

}
