<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Burger;
use App\Entity\Complement;
use App\Entity\Frite;
use App\Entity\Image;
use App\Entity\Menu;
use App\Form\BoissonType;
use App\Form\BurgerType;
use App\Form\ComplementType;
use App\Form\FriteType;
use App\Form\MenuType;
use App\Form\UserType;
use App\Repository\BoissonRepository;
use App\Repository\BurgerRepository;
use App\Repository\CommandeRepository;
use App\Repository\ComplementRepository;
use App\Repository\FriteRepository;
use App\Repository\MenuRepository;
use App\Repository\MessageRepository;
use App\Repository\PaiementRepository;
use App\Repository\RepasRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/admin", name="admn")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/acceuil/admin", name="admin")
     */
    public function home(MessageRepository $rep, PaiementRepository $rep1, SessionInterface $session): Response
    {
        if($this->isGranted("ROLE_GESTIONNAIRE")){
            $messages= $rep->findAll();
            $paiements= $rep1->findAll();
            $now= new DateTime('NOW');
            //recette total
            $total= 0;
            //recette du jour
            $journalier=0;
            //paiements non effectués
            $notPaid=0;
            //tous les burgers du jours
            $getBurgerOfDay=[];
            //repas le plus vendu
            $Sold=[];
            foreach ($paiements as $p) {
                if($p->getDate()==null){
                    $notPaid +=1;
                }else{
                    $total += $p->getMontant();
                    if ($p->getDate()->format("Y-m-d") == $now->format("Y-m-d")) {
                        $journalier += $p->getMontant();
                        foreach ($p->getCommande()->getPaniers() as $panier) {
                            if ($panier->getRepas()->getCategorie() == "BURGER") {
                                if(in_array($panier->getRepas()->getLibelle(), array_keys($getBurgerOfDay))){
                                    $getBurgerOfDay[$panier->getRepas()->getLibelle()] +=1 ;
                                }else{
                                    $getBurgerOfDay[$panier->getRepas()->getLibelle()] = 1;
                                }
                                
                            }
                        }
                    }
                    foreach ($p->getCommande()->getPaniers() as $panier) {
                        if ($panier->getRepas()->getCategorie() == "BURGER") {
                            if (in_array($panier->getRepas()->getLibelle(), array_keys($Sold))) {
                                $Sold[$panier->getRepas()->getLibelle()] += 1;
                            } else {
                                $Sold[$panier->getRepas()->getLibelle()] = 1;
                            }
                        }
                    }
                }
            }
            
            if($getBurgerOfDay!=[]){
                $bOfDay= array_keys($getBurgerOfDay, max($getBurgerOfDay))[0];
            }else{
                $bOfDay="Aucun";
            }
            if ($Sold!=[]) {
                $mSold = array_keys($Sold, max($Sold))[0];
            } else {
                $mSold = "aucun";
            }
            $session->set('messages', $messages);
            $session->set('impaye', $notPaid);
            return $this->render('admin/index.html.twig', [
                "total"=>$total,
                "journalier"=>$journalier,
                "burgerDuJourLePlusVendu"=> $bOfDay,
                "mostSold"=> $mSold,
                "paiements"=>$paiements
            ]);
        }
        return $this->redirectToRoute('home');
        
    }


    /**
     * @Route("/acceuil/admin/liste/burger", name="liste_burger")
     */
    public function listeBurger(BurgerRepository $rep): Response
    {
        $burgers=$rep->findAll();
        return $this->render('admin/liste.burger.html.twig', [
            "burgers"=>$burgers,
        ]);
    }

    /**
     * @Route("/acceuil/admin/liste/frite", name="liste_frite")
     */
    public function listeFrite(FriteRepository $rep): Response
    {
        $frites= $rep->findAll();
        return $this->render('admin/liste.frite.html.twig', compact("frites"));
    }

    /**
     * @Route("/acceuil/admin/liste/menu", name="liste_menu")
     */
    public function listeMenu(MenuRepository $rep): Response
    {
        $menus= $rep->findAll();
        return $this->render('admin/liste.menu.html.twig', [
            'menus'=>$menus
        ]);
    }

    /**
     * @Route("/acceuil/admin/liste/boisson", name="liste_boisson")
     */
    public function listeBoisson(BoissonRepository $rep): Response
    {
        $boissons= $rep->findAll();
        return $this->render('admin/liste.boisson.html.twig', [
            'boissons'=>$boissons
        ]);
    }

    /**
     * @Route("/acceuil/admin/liste/complement", name="liste_complement")
     */
    public function listeComplement(ComplementRepository $rep): Response
    {
        $complements= $rep->findAll();
        return $this->render('admin/liste.complement.html.twig', [
            "complements"=>$complements
        ]);
    }

    /**
     * @Route("/acceuil/admin/add/burger/{id}", name="add_burger")
     */
    public function addBurger(EntityManagerInterface $manager, Request $request, BurgerRepository $rep,$id): Response
    {
        if($id==0){
            $burger = new Burger();
            $burger->setCategorie('BURGER');
        }else{
            $burger= $rep->find($id);
        }
        
        $form = $this->createForm(BurgerType::class, $burger);
        if($request->request->count()>0){
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) { 
                $images=$form->get("images")->getData();
                foreach ($images as $image) {
                    $filename = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move($this->getParameter('burger_directory'), $filename);
                    $img= new Image();
                    $img->setLibelle($filename);
                    $burger->addImage($img);
                    $manager->persist($img);
                }
                $manager->persist($burger);
                $manager->flush();
                return $this->redirectToRoute('liste_burger');
            }
        }
        return $this->render('admin/add.burger.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/acceuil/admin/del/burger/{id}", name="del_burger")
     */
    public function deleteBurger($id, Request $request, EntityManagerInterface $manager, BurgerRepository $rep)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $burger = $rep->findOneBy(["id" => $id]);
            foreach ($burger->getImages() as $img) {
                $burger->removeImage($img);
                $manager->remove($img);
                $manager->flush();
            }
            $manager->remove($burger);
            $manager->flush();
        }
    }


    /**
     * @Route("/acceuil/admin/add/boisson/{id}", name="add_boisson")
     */
    public function addBoisson(EntityManagerInterface $manager, Request $request, BoissonRepository $rep, $id): Response
    {
        if ($id == 0) {
            $boisson = new Boisson();
            $boisson->setCategorie('BOISSON');
        } else {
            $boisson = $rep->find($id);
        }

        $form = $this->createForm(BoissonType::class, $boisson);
        if ($request->request->count() > 0) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get("images")->getData();
                foreach ($images as $image) {
                    $filename = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move($this->getParameter('boisson_directory'), $filename);
                    $img = new Image();
                    $img->setLibelle($filename);
                    $boisson->addImage($img);
                    $manager->persist($img);
                }
                $manager->persist($boisson);
                $manager->flush();
                return $this->redirectToRoute('liste_boisson');
            }
        }
        return $this->render('admin/add.boisson.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acceuil/admin/del/boisson/{id}", name="del_boisson")
     */
    public function deleteBoisson($id, Request $request, EntityManagerInterface $manager, BoissonRepository $rep)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $boisson = $rep->findOneBy(["id" => $id]);
            foreach ($boisson->getImages() as $img) {
                $boisson->removeImage($img);
                $manager->remove($img);
                $manager->flush();
            }
            $manager->remove($boisson);
            $manager->flush();
        }
    }

    /**
     * @Route("/acceuil/admin/add/frite/{id}", name="add_frite")
     */
    public function addFrite(EntityManagerInterface $manager, Request $request, FriteRepository $rep, $id): Response
    {
        if ($id == 0) {
            $frite = new Frite();
            $frite->setCategorie('FRITE');
        } else {
            $frite = $rep->find($id);
        }

        $form = $this->createForm(FriteType::class, $frite);
        if ($request->request->count() > 0) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get("images")->getData();
                foreach ($images as $image) {
                    $filename = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move($this->getParameter('frite_directory'), $filename);
                    $img = new Image();
                    $img->setLibelle($filename);
                    $frite->addImage($img);
                    $manager->persist($img);
                }
                $manager->persist($frite);
                $manager->flush();
                return $this->redirectToRoute('liste_frite');
            }
        }
        return $this->render('admin/add.frite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acceuil/admin/del/frite/{id}", name="del_frite")
     */
    public function deleteFrite($id, Request $request, EntityManagerInterface $manager, FriteRepository $rep)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $frite = $rep->findOneBy(["id" => $id]);
            foreach ($frite->getImages() as $img) {
                $frite->removeImage($img);
                $manager->remove($img);
                $manager->flush();
            }
            $manager->remove($frite);
            $manager->flush();
        }
    }

    /**
     * @Route("/acceuil/admin/add/menu/{id}", name="add_menu")
     */
    public function addMenu(EntityManagerInterface $manager, Request $request, MenuRepository $rep, BurgerRepository $rep1, FriteRepository $rep2, BoissonRepository $rep3, $id): Response
    {
        if ($id == 0) {
            $menu = new Menu();
            $menu->setCategorie('MENU');
        } else {
            $menu = $rep->find($id);
        }

        $form = $this->createForm(MenuType::class, $menu);
        if ($request->request->count() > 0) {
            $form->handleRequest($request);
            $data= $request->request;
            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get("images")->getData();

                foreach ($images as $image) {
                    $filename = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move($this->getParameter('menu_directory'), $filename);
                    $img = new Image();
                    $img->setLibelle($filename);
                    $img->setRepas($menu);
                    $manager->persist($img);
                }
                if($data->get("menu")["burgers"]){
                    $burger= $rep1->find($data->get("menu")["burgers"]);
                    $menu->addRepa($burger);
                }
                if($data->get("menu")["frites"]){
                    $frite=$rep2->find($data->get("menu")["frites"]);
                    $menu->addRepa($frite);
                }
                if($data->get("menu")["boissons"]){
                    $boisson= $rep3->find($data->get("menu")["boissons"]);
                    $menu->addRepa($boisson);
                }
                $manager->persist($menu);
                $manager->flush();
                return $this->redirectToRoute('liste_menu');
            }
        }
        return $this->render('admin/add.menu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acceuil/admin/del/menu/{id}", name="del_menu")
     */
    public function deleteMenu($id, Request $request, EntityManagerInterface $manager, RepasRepository $rep)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $menu = $rep->findOneBy(["id" => $id]);
            foreach ($menu->getImages() as $img) {
                $menu->removeImage($img);
                $manager->remove($img);
                $manager->flush();
            }
            $manager->remove($menu);
            $manager->flush();
        }
    }


    /**
     * @Route("/acceuil/admin/add/complement/{id}", name="add_complement")
     */
    public function addComplement(EntityManagerInterface $manager, Request $request ,ComplementRepository $rep, BurgerRepository $rep1, FriteRepository $rep2, BoissonRepository $rep3 , $id): Response
    {
        if ($id == 0) {
            $complement = new Complement();
            $complement->setCategorie('COMPLEMENT');
        } else {
            $complement = $rep->find($id);
        }

        $form = $this->createForm(ComplementType::class, $complement);
        if ($request->request->count() > 0) {
            $form->handleRequest($request);
            $data = $request->request;

            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get("images")->getData();
                foreach ($images as $image) {
                    $filename = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move($this->getParameter('complement_directory'), $filename);
                    $img = new Image();
                    $img->setLibelle($filename);
                    $img->setRepas($complement);
                    $manager->persist($img);
                }
                if ($data->get("complement")["burgers"]) {
                    $burger = $rep1->find($data->get("complement")["burgers"]);
                    $complement->addRepa($burger);
                }
                if ($data->get("complement")["frites"]) {
                    $frite = $rep2->find($data->get("complement")["frites"]);
                    $complement->addRepa($frite);
                }
                if ($data->get("complement")["boissons"]) {
                    $boisson = $rep3->find($data->get("complement")["boissons"]);
                    $complement->addRepa($boisson);
                }
                $manager->persist($complement);
                $manager->flush();
                return $this->redirectToRoute('liste_complement');
            }
        }
        return $this->render('admin/add.complement.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acceuil/admin/del/complement/{id}", name="del_complement")
     */
    public function deleteComplement($id, Request $request, EntityManagerInterface $manager, ComplementRepository $rep)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $complement = $rep->findOneBy(["id" => $id]);
            foreach ($complement->getImages() as $img) {
                $complement->removeImage($img);
                $manager->remove($img);
                $manager->flush();
            }
            $manager->remove($complement);
            $manager->flush();
        }
    }

    /**
     * @Route("/acceuil/admin/profil", name="profil")
     */
    public function profil(Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User user */
            $user=$this->getUser();
            $encodedPassword = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $manager->flush();
        }

        return $this->render('security/profil.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("acceuil/admin/liste/commande/{etat}", name="liste_com")
     */
    public function ListerCommande($etat, CommandeRepository $rep): Response
    {
        $commandes = $rep->findBy(["etat" => $etat]);
        return $this->render('admin/liste.commande.html.twig', [
            "commandes" => $commandes
        ]);
    }

    /**
     * @Route("acceuil/admin/commande/annuler/{id}", name="del_com")
     */
    public function AnnulerCommande($id, EntityManagerInterface $manager, CommandeRepository $rep, SessionInterface $session): Response
    {
        $commande = $rep->find($id);
        $commande->setEtat("archivé");
        $manager->flush();
        return $this->redirectToRoute('liste_com');
    }

    /**
     * @Route("acceuil/admin/paiement/liste/{type}", name="liste_paiement")
     */
    public function ListePaiement($type, PaiementRepository $rep): Response
    {
        $paiements=$rep->findAll();
        $result1=[];
        $result2=[];
        foreach ($paiements as $p) {
            if ($p->getDate() == null) {
                $result1[]=$p;
            }else{
                $result2[]=$p;
            }
        }
        if($type=="paid"){
            $paie= $result2;
        }
        if($type=="notPaid"){
            $paie = $result1;
        }
        return $this->render('admin/liste.paiement.html.twig', [
            "paiements"=>$paie,
            "type"=>$type
            
        ]);
    }

    /**
     * @Route("acceuil/admin/paiement/annuler/{id}", name="del_paiement")
     */
    public function deletePaiement($id, EntityManagerInterface $manager, PaiementRepository $rep): Response
    {
        $paiement = $rep->find($id);
        $comm=$paiement->getCommande();
        $panier=$comm->getPaniers();
        foreach ($panier as $p) {
            $manager->remove($p);
        }
        $manager->remove($paiement);
        $manager->flush();
        return $this->redirectToRoute('liste_paiement', ['type'=>'notPaid']);
    }

    /**
     * @Route("acceuil/admin/encaisser/{id}", name="encaisser")
     */
    public function Encaisser($id, PaiementRepository $rep, EntityManagerInterface $manager, Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $paiement= $rep->find($id);
            $now = new DateTime('NOW');
            $paiement->setDate($now);
            $manager->flush();
            return $this->redirectToRoute('liste_paiement');
        }
    }

}
