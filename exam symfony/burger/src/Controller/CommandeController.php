<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Entity\Panier;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\ComplementRepository;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client as RestClient;

class CommandeController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/commande", name="commande")
     */
    public function index(SessionInterface $session, Request $request, ClientRepository $rep,
        EntityManagerInterface $manager, ComplementRepository $rep1, CommandeRepository $rep2,
        NotifierInterface $notifier): Response {
        $complements = $rep1->findAll();
        if($this->getUser() && !in_array("ROLE_GESTIONNAIRE", $this->getUser()->getRoles())){
            /** @var Client $client */
            $client = $this->getUser();
        } else {
            $client = new Client();
            $client->setRoles(["ROLE_CLIENT"]);
            $client->setEmail("default" . random_int(0, 255) . "@gmail.com");
            $pass="default";
            $client->setPassword($this->encoder->hashPassword($client, $pass));
        }
        
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $c = $rep->findOneBy(['telephone' => $client->getTelephone()]);
            if (is_null($c)) {
                $manager->persist($client);
                $manager->flush();
                // Your Account SID and Auth Token from twilio.com/console
                // Create a Notification that has to be sent
                $notifier->send(new Notification('vos coordonnées ont été créés par défaut et sont email('. $client->getEmail().') password(default)', ['browser']));
                
            }
            $session->set('client', $client);
            return $this->redirectToRoute('commande');
        }
        if($session->get('idCommande')){
            $commande = $rep2->find($session->get('idCommande'));
            return $this->render('commande/cart.html.twig', [
                'commande' => $commande,
                "cactive" => "active",
                'form' => $form->createView(),
                'complements' => $complements
            ]);
        }else{
            return $this->redirectToRoute('home');
        }
        
    }

    /**
     * @Route("/commande/cart/{id}", name="cart")
     */
    public function Cart($id, SessionInterface $session, RepasRepository $rep, EntityManagerInterface $manager, CommandeRepository $rep1) {
        if ($session->get('idCommande') != null) {
            $commande = $rep1->find($session->get('idCommande'));
        } else {
            $commande = new Commande();
            $commande->setEtat("en cours");
        }
        $repas=$rep->find($id);
        $commande->setNombre($commande->getNombre() + 1);
        $commande->setTotal($commande->getTotal() + $repas->getPrix());
        $panier= new Panier();
        $panier->setRepas($repas);
        $commande->addPanier($panier);
        $manager->persist($commande);
        $manager->flush();
        $session->set('idCommande', $commande->getId());
        $session->set('nombre', $commande->getNombre());
        return $this->redirectToRoute('menu');
    }

    /**
     * @Route("/commande/finaliser", name="finaliser")
     */
    public function Finaliser(Request $request, ComplementRepository $rep, SessionInterface $session, ObjectManager $manager, 
    CommandeRepository $rep1, ClientRepository $rep2, MailerInterface $mailer): Response {
        $co=$rep->findAll();
        $commande = $rep1->find($session->get('idCommande'));
        $commande->setEtat("validé");
        /** @var Repas[] $complements */
        $complements = $request->request->get('complement');
        if ($complements != null) {
            foreach ($complements as $id) {
                $complement = $rep->find(["id" => $id]);
                $commande->setNombre($commande->getNombre() + 1);
                $commande->setTotal($commande->getTotal() + $complement->getPrix());
                $panier= new Panier();
                $panier->setRepas($complement);
                $commande->addPanier($panier);
            }
            $manager->flush();
        }
        if($commande){
            $paiement= new Paiement();
            $client=$rep2->findOneBy(['telephone'=>$session->get('client')->getTelephone()]);
            $total = $commande->getTotal();
            $paiement->setCommande($commande);
            $paiement->setMontant($commande->getTotal());
            $manager->persist($paiement);
            $manager->flush();
            $client->addPaiement($paiement);
            $manager->persist($client);
            $manager->flush();
            $session->remove('idCommande');
            $session->remove('nombre');
        }else{
            $total=0;
        }

        $email = (new Email())
            ->from("brazilburger@gmail.com")
            ->to($client->getEmail())
            ->subject('avis de commande')
            ->text('Brazil burger')
            ->html('<p>Votre commande est en cuisine;<br> veuilez patienter quelques minutes</p>Cordialement Brazil Burger');
        $mailer->send($email);
        return $this->render('commande/cart.html.twig', [
            'final' => 'final',
            'total' =>$total,
            'complements'=>$co,
            'numero'=>$commande->getId()
        ]);
    }

    /**
     * @Route("/commande/update/{type}/{id}", name="update")
     */
    public function update($type, $id, SessionInterface $session, ObjectManager $manager, CommandeRepository $rep, RepasRepository $rep1)
    {
        $commande = $rep->find($session->get('idCommande'));
        $repas    = $rep1->find($id);
        if($type=="increase"){
            $commande->setNombre($commande->getNombre() + 1);
            $commande->setTotal($commande->getTotal() + $repas->getPrix());
            $panier= new Panier();
            $panier->setRepas($repas);
            $commande->addPanier($panier);
        }else if($type=="decrease"){
            $commande->setNombre($commande->getNombre() - 1);
            $commande->setTotal($commande->getTotal() - $repas->getPrix());
            foreach ($commande->getPaniers() as $panier) {
                if($panier->getRepas()==$repas){
                    $commande->removePanier($panier);
                    $manager->remove($panier);
                }
                break;
            }
        }
        if ($commande->getNombre() == 0) {
            $session->remove('idCommande');
            $session->remove('nombre');
            $manager->remove($commande);
        }else{
            $session->set('idCommande', $commande->getId());
            $session->set('nombre', $commande->getNombre());
            $manager->persist($commande);
        }
        $manager->flush();
        return $this->redirectToRoute('commande');
    }


    /**
     * @Route("/commande/annuler", name="annuler_com")
     */
    public function AnnulerCommande(SessionInterface $session, EntityManagerInterface $manager, CommandeRepository $rep): Response
    {
        $commande = $rep->find($session->get('idCommande'));
        $commande->setEtat("archivé");
        $manager->flush();
        $session->remove('idCommande');
        $session->remove('nombre');
        return $this->redirectToRoute('menu');
    }


    /**
     * @Route("/client/commande", name="client_commande")
     */
    public function clientCommande(CommandeRepository $rep): Response
    {
        /** @var Client */
        $client=$this->getUser();
        $paiements=$client->getPaiements();
        $commandes=[];
        foreach ($paiements as $p) {
            $commandes[]=$p->getCommande();
        }
        return $this->render('commande/client.commande.html.twig', [
            "commandes"=>$commandes,
            "vactive"=>"active"
        ]);
    }

}
