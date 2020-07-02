<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppController  extends AbstractController
{

    private $em;
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
     
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(UserPasswordEncoderInterface $passwordEncoder)
    {
      /*$user = new User();

        $username ="hitadymg";

        $email = "hitady@gmail.com";

        $password ="hitady@miraygeek";
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles(["ROLE_ADMIN"]);
        $this->em->persist($user);
        $this->em->flush();*/
        return $this->render('index.html.twig',[]);
    }

    /**
     * @Route("/mg/hitady/admin",name="admin")
     */
    public function admin()
    {
        return $this->render('admin.html.twig', [

        ]);
    }

}
