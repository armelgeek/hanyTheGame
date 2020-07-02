<?php

namespace App\Services;


use App\Entity\EmailVerification;
use App\Repository\EmailVerificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProfileService
{

    private  $tokenGeneratorService;
    private  $emailVerificationRepository;
    private  $dispatcher;

    public function __construct(
        TokenGeneratorService $tokenGeneratorService,
        EmailVerificationRepository $emailVerificationRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->tokenGeneratorService = $tokenGeneratorService;
        $this->emailVerificationRepository = $emailVerificationRepository;
        $this->dispatcher = $dispatcher;
    }

    public function updateAvatar(AvatarDto $data): void
    {
        if ($data->file->getRealPath() === false) {
            throw new \RuntimeException('Impossible de redimensionner un avatar non existant');
        }
        // On redimensionne l'image
    //    $manager = new ImageManager(['driver' => 'imagick']);
    //    $manager->make($data->file)->fit(110, 110)->save($data->file->getRealPath());

        // On la déplace dans le profil utilisateur
    //    $data->user->setAvatarFile($data->file);
    //    $data->user->setUpdatedAt(new \DateTime());
    }



    public function updateEmail(EmailVerification $emailVerification, EntityManagerInterface $em): void
    {
        $emailVerification->getAuthor()->setEmail($emailVerification->getEmail());
        $em->remove($emailVerification);
    }

}
