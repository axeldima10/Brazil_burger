<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Form\UserType;
use App\Repository\BoissonRepository;
use App\Repository\BurgerRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\FriteRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class HomeController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            "hactive"=>"active"
        ]);
    }

    /**
     * @Route("/profil", name="show_profil")
     */
    public function profil(Request $request, EntityManagerInterface $manager, CommandeRepository $com): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $manager->flush();
        }

        return $this->render('home/profil.html.twig', [
            'form' => $form->createView(), 
            "pactive" => "active"
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EntityManagerInterface $manager): Response
    {
        $message= new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $this->addFlash("sent", "message envoyé avec succès");
            $manager->persist($message);
            $manager->flush();
            return $this->redirectToRoute('contact');
        }
        return $this->render('home/contact.html.twig', [
            'form'=>$form->createView(),
            'Cactive'=>"active"
        ]);
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function Menu(MenuRepository $rep, BoissonRepository $rep1, FriteRepository $rep2, BurgerRepository $rep3, SessionInterface $session): Response
    {
        $session->remove('client');
        $menus = $rep->findAll();
        $boissons = $rep1->findAll();
        $frites = $rep2->findAll();
        $burgers= $rep3->findAll();
        return $this->render('home/menu.html.twig', [
            "mactive"=>"active",
            "menus"=>$menus,
            "boissons"=>$boissons,
            "frites"=>$frites,
            "burgers"=>$burgers
        ]);
    }
}
