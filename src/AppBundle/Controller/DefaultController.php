<?php
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Arrivee;
use AppBundle\Entity\Expediteurs;
use AppBundle\Form\FormArrivee;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AppBundle\Entity\Departements;
use AppBundle\Entity\Profils;
use AppBundle\Entity\Controles;
use AppBundle\Entity\Actions;
use AppBundle\Entity\User;
use AppBundle\Entity\Services;
use AppBundle\Entity\Notifications;
use AppBundle\Entity\CourriersArrivee;

use Symfony\Component\Validator\Constraints\NotNull;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function showAction(Request $request)
    {


        return $this->render('auth/login.html.twig');
    }

    /**
     * @Route("/addarrivee", name="addarrivee")
     */
    public function addarriveeAction(Request $request)
    {
        
       
        $em = $this->getDoctrine()->getManager();
        $arrivee = new Arrivee();
        $formArrivee = $this->createForm(FormArrivee::class, $arrivee);
        $formArrivee->handleRequest($request);
        

        if ( $formArrivee->isSubmitted() && $formArrivee->isValid()) {
          
            $Expediteur = new Expediteurs();


            

            $Expediteur->setExpediteur($request->get('Expediteur'));
            $Expediteur->setTel($request->get('Tel'));
            $Expediteur->setFax($request->get('Fax'));
            $em->persist($Expediteur);
            $em->flush();


            $dateCreation = new \DateTime();
           //var_dump($request->get('Nom'),$request->get('Prenom'),$request->get('Civilite'));die;
            $arrivee->setDateCreation($dateCreation);
            $arrivee->setExpediteur($Expediteur);

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $arrivee->getFichier();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('brochures_directory'),
                    $fileName
                );
            } catch (FileException $e) {
               

            }
            $arrivee->setFichier($fileName);
            $arrivee->setStatut(0);
            $em->persist($arrivee);
            $em->flush();

            // Envoi Message et notification  au directeur
            $directeur= $em->getRepository('AppBundle:User')->findOneBy( array('Role'=>'ROLE_DIRECTEUR'));
            $courrierArrivee = new CourriersArrivee();
            $courrierArrivee->setArrivee($arrivee);
            $courrierArrivee->setUser($directeur);
            $em->persist($courrierArrivee);
            $em->flush();
            $notification = new Notifications();
            $notification->setArrivee($arrivee);
            $notification->setUser($directeur);
            $notification->setStatut(1);
            $em->persist($notification);
            $em->flush();
            // Envoi Message au bureau d'ordre
            $bureauOrdre= $em->getRepository('AppBundle:User')->findOneBy( array('Role'=>'ROLE_BUREAU_ORDRE'));
            $courrierArrivee = new CourriersArrivee();
            $courrierArrivee->setArrivee($arrivee);
            $courrierArrivee->setUser($bureauOrdre);
            $em->persist($courrierArrivee);
            $em->flush();




            return $this->redirectToRoute('ListeArrivee');
        }

        // $lastArrivee = $em->getRepository('AppBundle:Arrivee')->findOneBy(array(), array('id' => 'DESC'));
        // var_dump($lastArrivee->getId());die;
        return $this->render('Arrivees/ajouterArrivee.html.twig', [
            'formArrivee' => $formArrivee->createView(),
        ]);
    }



     /**
     * @Route("/paramarrivee/{id}/", name="paramarrivee")
     */
    public function paramarriveeAction(Request $request, $id)
    {
        
       
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Arrivee');
        $arrivee = $repository->find($id);
        $departements = $em->getRepository('AppBundle:Departements')->findAll();
        $directionControles = $em->getRepository('AppBundle:Controles')->findBy(array('pave'=>'Direction'));
        $divisionControles = $em->getRepository('AppBundle:Controles')->findBy(array('pave'=>'Division'));
        $repository = $em->getRepository('AppBundle:Actions');
        $actions = $repository->findBy(array('arrivee'=>$arrivee));

       // var_dump($arrivee->getDepartement()->getId());die;
       if ($arrivee->getDepartement() != null){
            $repository = $em->getRepository('AppBundle:Services');
            $services = $repository->findBy(array('departement'=>$arrivee->getDepartement()));
        
            return $this->render('Arrivees/ParamArrivee.html.twig', array( 'arrivee'=> $arrivee,'departements'=>$departements,'directionControles'=>$directionControles, 'divisionControles' => $divisionControles ,'actions'=>$actions , 'services'=>$services));
       }
       else
       {
            return $this->render('Arrivees/ParamArrivee.html.twig', array( 'arrivee'=> $arrivee,'departements'=>$departements,'directionControles'=>$directionControles, 'divisionControles' => $divisionControles ,'actions'=>$actions));
       }
       
    }

     /**
     * @Route("/Nextarrivee", name="Nextarrivee")
     */
    public function NextarriveeAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();
        
         // modif arrivee
         $IdArrivee=$request->get('IdArrivee');
         $repository = $em->getRepository('AppBundle:Arrivee');
         $arrivee = $repository->find($IdArrivee);
         $IdExpediteur=$arrivee->getExpediteur()->getId();
         $repository = $em->getRepository('AppBundle:Expediteurs');
         $expediteur = $repository->find($IdExpediteur);
        // seuls le directeur et bureau d'ordre qu'ont le droit de modifier le pavé courrier-expéditeur
        if($user->getRole()=='ROLE_DIRECTEUR' or $user->getRole()=='ROLE_BUREAU_ORDRE' ){
            $expediteur->setExpediteur($request->get('Expediteur'));
            $expediteur->setTel($request->get('Tel'));
            $expediteur->setFax($request->get('Fax'));
            $arrivee->setRefCourrier($request->get('RefCourrier'));
            $arrivee->setType($request->get('Type'));
            $arrivee->setObjet($request->get('Objet'));
            $dateCourrier = new \DateTime($request->get('DateCourrier'));
            $dateArrivee = new \DateTime($request->get('DateArrivee'));
            $arrivee->setDateCourrier($dateCourrier);
            $arrivee->setDateArrivee($dateArrivee);
            if($user->getRole()=='ROLE_DIRECTEUR'){
                // le directeur à le droit de modifier le reste
                $repository = $em->getRepository('AppBundle:Departements');
                $departement = $repository->find($request->get('Departement'));
                if($arrivee->getDepartement()!= null){
                    if( (string)$arrivee->getDepartement()->getId() != $request->get('Departement') and $arrivee->getDepartement()->getChef()->getRole() != 'ROLE_DIRECTEUR' ){
                        // supprimer les anciennes notifications et message
    
                        //suppression anciens messages département
                        $repository = $em->getRepository('AppBundle:Departements');
                        $ancienDepartement = $repository->find($arrivee->getDepartement()->getId());
                        $anciensDepartementMessages= $em->getRepository('AppBundle:CourriersArrivee')->findBy( array('arrivee'=>$arrivee, 'user'=>$ancienDepartement->getChef()));
                        foreach($anciensDepartementMessages as $message)
                        {
                            $em->remove($message);
                            $em->flush();
                        }
    
                        //suppression anciens notifications département
                        $anciensDepartementNotif= $em->getRepository('AppBundle:Notifications')->findBy( array('arrivee'=>$arrivee, 'user'=>$ancienDepartement->getChef()));
                        foreach($anciensDepartementNotif as $notification)
                        {
                            $em->remove($notification);
                            $em->flush();
                        }

                       if ($arrivee->getService() != null) {
                           //suppression anciens messages service
                        $repository = $em->getRepository('AppBundle:Services');
                        $ancienService = $repository->find($arrivee->getService()->getId());
                        $anciensServiceMessages= $em->getRepository('AppBundle:CourriersArrivee')->findBy( array('arrivee'=>$arrivee, 'user'=>$ancienService->getChef()));
                        foreach($anciensServiceMessages as $message)
                        {
                            $em->remove($message);
                            $em->flush();
                        }

                        //suppression anciens notifications service
                        $anciensServiceNotif= $em->getRepository('AppBundle:Notifications')->findBy( array('arrivee'=>$arrivee, 'user'=>$ancienService->getChef()));
                        foreach($anciensServiceNotif as $notification)
                        {
                            $em->remove($notification);
                            $em->flush();
                        }

                       }
    
                        // remetrre service null car le département a été changé
                        $arrivee->setService(null);

    
                    }

                }
               
                
                $arrivee->setDepartement($departement);
                //pavé service
                if($request->get('Service') != null){
                    $repository = $em->getRepository('AppBundle:Services');
                    $service = $repository->find($request->get('Service'));
                    $arrivee->setService($service);
                    //tester si c'est déja envoyé au service concerné ou pas
                    $envoiServ= $em->getRepository('AppBundle:CourriersArrivee')->findOneBy( array('arrivee'=>$arrivee, 'user'=>$service->getChef()));
                    if ($envoiServ == null){
                        // Envoi Message et notification au service concerné
                        $courrierArrivee = new CourriersArrivee();
                        $courrierArrivee->setArrivee($arrivee);
                        $courrierArrivee->setUser($service->getChef());
                        $em->persist($courrierArrivee);
                        $em->flush();
                        $notification = new Notifications();
                        $notification->setArrivee($arrivee);
                        $notification->setUser($service->getChef());
                        $notification->setStatut(1);
                        $em->persist($notification);
                        $em->flush();
                    }
                }
                
                $directeur= $em->getRepository('AppBundle:User')->findOneBy( array('Role'=>'ROLE_DIRECTEUR'));
                if($departement->getChef()->getId() == $directeur->getId()){
                    // statut 4 Courrier Traité
                    $arrivee->setStatut(4);
                }else{
                    // statut 1 envoyer a un autre dépatement
                    $arrivee->setStatut(1);

                    //tester si c'est déja envoyé ou pas
                    $envoiDep= $em->getRepository('AppBundle:CourriersArrivee')->findOneBy( array('arrivee'=>$arrivee, 'user'=>$departement->getChef()));
                    if ($envoiDep == null){
                        // Envoi Message et notification au département concerné
                        $courrierArrivee = new CourriersArrivee();
                        $courrierArrivee->setArrivee($arrivee);
                        $courrierArrivee->setUser($departement->getChef());
                        $em->persist($courrierArrivee);
                        $em->flush();
                        $notification = new Notifications();
                        $notification->setArrivee($arrivee);
                        $notification->setUser($departement->getChef());
                        $notification->setStatut(1);
                        $em->persist($notification);
                        $em->flush();
                    }
                   
                }
                
                //  retenir les actions
                $controles = $em->getRepository('AppBundle:Controles')->findAll();
        
                foreach($controles as $controle)
                            {
                                
                                $repository = $em->getRepository('AppBundle:Actions');
                                
                                $action = $repository->findOneBy(array('arrivee'=>$arrivee,'controle'=>$controle));
                               
                                if ($action ){
                                    $action->setControle($controle);
                                    $action->setValue($request->get('controle_'.$controle->getId()));
                                    $action->setArrivee($arrivee);
                                    
                                    $em->flush();
        
                                }else{
                                    $Newaction = new Actions();
                                    $Newaction->setControle($controle);
                                    $Newaction->setValue($request->get('controle_'.$controle->getId()));
                                    $Newaction->setArrivee($arrivee);
                                    
                                    $em->persist($Newaction);
                                    $em->flush();
        
                                }
                            }

            }


        }
       
        // le chef de division peut modifier le pavé service
        if($user->getRole()=='ROLE_DIVISION'  ){
            $repository = $em->getRepository('AppBundle:Services');
            $service = $repository->find($request->get('Service'));
            $arrivee->setService($service);
            //pavé service
            if($request->get('Service') != null){
                $repository = $em->getRepository('AppBundle:Services');
                $service = $repository->find($request->get('Service'));
                $arrivee->setService($service);
                //tester si c'est déja envoyé ou pas
                $envoiServ= $em->getRepository('AppBundle:CourriersArrivee')->findOneBy( array('arrivee'=>$arrivee, 'user'=>$service->getChef()));
                if ($envoiServ == null){
                    // Envoi Message et notification au département concerné
                    $courrierArrivee = new CourriersArrivee();
                    $courrierArrivee->setArrivee($arrivee);
                    $courrierArrivee->setUser($service->getChef());
                    $em->persist($courrierArrivee);
                    $em->flush();
                    $notification = new Notifications();
                    $notification->setArrivee($arrivee);
                    $notification->setUser($service->getChef());
                    $notification->setStatut(1);
                    $em->persist($notification);
                    $em->flush();
                }
            }
            //  retenir les actions
            $divisionControles = $em->getRepository('AppBundle:Controles')->findBy(array('pave'=>'Division'));
                
            foreach($divisionControles as $controle)
                        {
                                        
                            $repository = $em->getRepository('AppBundle:Actions');
                                        
                            $action = $repository->findOneBy(array('arrivee'=>$arrivee,'controle'=>$controle));
                                       
                            if ($action ){
                                $action->setControle($controle);
                                $action->setValue($request->get('controle_'.$controle->getId()));
                                $action->setArrivee($arrivee);
                                $em->flush();
                
                            }else{
                                $Newaction = new Actions();
                                $Newaction->setControle($controle);
                                $Newaction->setValue($request->get('controle_'.$controle->getId()));
                                $Newaction->setArrivee($arrivee);
                                $em->persist($Newaction);
                                $em->flush();
                
                            }
                        }
        
            }
                    
        $em->flush();
        
       
      
        return $this->redirectToRoute('ListeArrivee');
    
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }


    /**
     * @Route("/ListeArrivee", name="ListeArrivee")
     */
    public function ListeArriveeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();
        $repository = $em->getRepository('AppBundle:CourriersArrivee');
        $courrierArrivees = $repository->findBy(array('user'=>$user));
        $arrivees = Array();
        foreach ($courrierArrivees as $courrierArrivee  ){
            array_push($arrivees, $courrierArrivee->getArrivee());
        }
        $arrivee = new Arrivee();
        $formArrivee = $this->createForm(FormArrivee::class, $arrivee);
        $formArrivee->handleRequest($request);

        return $this->render('Arrivees/ListeArrivees.html.twig', array( 'arrivees'=> $arrivees , 'formArrivee' => $formArrivee->createView()));
    }



    

     /**
      * @Route("/DeleteArrivee/{id}/", name="DeleteArrivee")
      */

    public function DeleteArriveeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Arrivee');
        $arrivee = $repository->find($id);

        //suppression Actions liés
        $repository = $em->getRepository('AppBundle:Actions');
        $actions = $repository->findBy(array('arrivee'=>$arrivee));

          foreach($actions as $action)
                    {
                        $em->remove($action);
                        $em->flush();
                    }
        //suppression courriers_arrivee liés
        $repository = $em->getRepository('AppBundle:CourriersArrivee');
        $courrierArrivees = $repository->findBy(array('arrivee'=>$arrivee)); 
        foreach($courrierArrivees as $courrierArrivee)
            {
                $em->remove($courrierArrivee);
                $em->flush();
            }
        //suppression notifications liés
        $repository = $em->getRepository('AppBundle:Notifications');
        $notifications = $repository->findBy(array('arrivee'=>$arrivee)); 
        foreach($notifications as $notification)
            {
                $em->remove($notification);
                $em->flush();
            }
        //supprimer arrivee
        $em->remove($arrivee);
        $em->flush();

        return $this->redirectToRoute('ListeArrivee');
    }

    /**
     * @Route("/AfficherArrivee/{id}/", name="AfficherArrivee")
     * @Method("GET")
     */

      public function AfficherArriveeAction(Request $request, $id)
      {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Arrivee');
        $arrivee = $repository->find($id);
       
            $encoders = array( new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);

            $jsonContent = $serializer->serialize($arrivee, 'json');
            return new Response($jsonContent);

            
      }

    /**
     * @Route("/ModifArrivee", name="ModifArrivee")
     */

    public function ModifArriveeAction( Request $request)
    {
        
        
        $IdArrivee=$request->get('IdArrivee');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Arrivee');
        $arrivee = $repository->find($IdArrivee);
        $IdExpediteur=$arrivee->getExpediteur()->getId();
        $repository = $em->getRepository('AppBundle:Expediteurs');
        $expediteur = $repository->find($IdExpediteur);
        $expediteur->setExpediteur($request->get('Expediteur'));
        $expediteur->setTel($request->get('Tel'));
        $expediteur->setFax($request->get('Fax'));
        $arrivee->setRefCourrier($request->get('RefCourrier'));
        $arrivee->setType($request->get('Type'));
        $arrivee->setObjet($request->get('Objet'));
        $dateCourrier = new \DateTime($request->get('DateCourrier'));
        $dateArrivee = new \DateTime($request->get('DateArrivee'));
        $arrivee->setDateCourrier($dateCourrier);
        $arrivee->setDateArrivee($dateArrivee);
        $em->flush();
      
        return $this->redirectToRoute('ListeArrivee');

          
    }


     /**
     * @Route("/ListeDepartements", name="ListeDepartements")
     */
    public function ListeDepartementsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $departements = $em->getRepository('AppBundle:Departements')->findAll();
        //$chefs = $em->getRepository('AppBundle:User')->findBy( array('Role'=>'ROLE_DIVISION'));
        $chefs = $em->getRepository('AppBundle:User')->findAll();
        
        return $this->render('Parametres/ListeDepartements.html.twig', array( 'departements'=> $departements , 'chefs' => $chefs));
    }

    

    /**
     * @Route("/AddDepartement", name="AddDepartement")
     */

    public function AddDepartementAction( Request $request)
    {
        
        
        $em = $this->getDoctrine()->getManager();
        $departement = new Departements();
        $departement->setDepartement($request->get('Departement'));
        $departement->setDescription($request->get('Description'));
        $departement->setType($request->get('Type'));
        $chef = $em->getRepository('AppBundle:User')->find($request->get('Chef'));
        $departement->setChef($chef);
        
        $em->persist($departement);
        $em->flush();
      
        return $this->redirectToRoute('ListeDepartements');

          
    }

    /**
     * @Route("/AfficherDepartement/{id}/", name="AfficherDepartement")
     * @Method("GET")
     */

    public function AfficherDepartementAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Departements');
      $departement = $repository->find($id);
     
          $encoders = array( new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);

          $jsonContent = $serializer->serialize($departement, 'json');
          return new Response($jsonContent);

          
    }

     /**
     * @Route("/ModifDepartement", name="ModifDepartement")
     */

    public function ModifDepartementAction( Request $request)
    {
        
        
        $IdDepartement=$request->get('IdDepartement');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Departements');
        $departement = $repository->find($IdDepartement);
        $departement->setDepartement($request->get('DepartementModal'));
        $departement->setDescription($request->get('DescriptionModal'));
        $departement->setType($request->get('TypeModal'));
        $chef = $em->getRepository('AppBundle:User')->find($request->get('ChefModal'));
        $departement->setChef($chef);
        $em->flush();
      
        return $this->redirectToRoute('ListeDepartements');

          
    }

     /**
      * @Route("/DeleteDepartement/{id}/", name="DeleteDepartement")
      */

      public function DeleteDepartementAction(Request $request, $id)
      {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:Departements');
          $departement = $repository->find($id);
          $em->remove($departement);
          $em->flush();
  
          return $this->redirectToRoute('ListeDepartements');
      }



    /**
     * @Route("/ListeProfils", name="ListeProfils")
     */
    public function ListeProfilsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Profils = $em->getRepository('AppBundle:Profils')->findAll();
        return $this->render('Parametres/ListeProfils.html.twig', array( 'Profils'=> $Profils));
    }

    

    /**
     * @Route("/AddProfil", name="AddProfil")
     */

    public function AddProfilAction( Request $request)
    {
        
        
        $em = $this->getDoctrine()->getManager();
        $profil = new Profils();
        $profil->setProfil($request->get('Profil'));
        $profil->setNiveau($request->get('Niveau'));
        
        $em->persist($profil);
        $em->flush();
      
        return $this->redirectToRoute('ListeProfils');

          
    }

    /**
     * @Route("/AfficherProfil/{id}/", name="AfficherProfil")
     * @Method("GET")
     */

    public function AfficherProfilAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Profils');
      $profil = $repository->find($id);
     
          $encoders = array( new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);

          $jsonContent = $serializer->serialize($profil, 'json');
          return new Response($jsonContent);

          
    }

     /**
     * @Route("/ModifProfil", name="ModifProfil")
     */

    public function ModifProfilAction( Request $request)
    {
        
        
        $IdProfil=$request->get('IdProfil');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Profils');
        $profil = $repository->find($IdProfil);
        $profil->setProfil($request->get('ProfilModal'));
        $profil->setNiveau($request->get('NiveauModal'));
        $em->flush();
      
        return $this->redirectToRoute('ListeProfils');

          
    }

     /**
      * @Route("/DeleteProfil/{id}/", name="DeleteProfil")
      */

      public function DeleteProfilAction(Request $request, $id)
      {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:Profils');
          $profil = $repository->find($id);
          $em->remove($profil);
          $em->flush();
  
          return $this->redirectToRoute('ListeProfils');
      }


    /**
     * @Route("/ListeControles", name="ListeControles")
     */
    public function ListeControlesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Controles = $em->getRepository('AppBundle:Controles')->findAll();
        return $this->render('Parametres/ListeControles.html.twig', array( 'Controles'=> $Controles ));
    }

    

    /**
     * @Route("/AddControle", name="AddControle")
     */

    public function AddControleAction( Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $Controle = new Controles();
        $Controle->setLabel($request->get('Label'));
        $Controle->setType($request->get('Type'));
        $Controle->setPave($request->get('Pave'));
        
        $em->persist($Controle);
        $em->flush();
      
        return $this->redirectToRoute('ListeControles');

          
    }

    /**
     * @Route("/AfficherControle/{id}/", name="AfficherControle")
     * @Method("GET")
     */

    public function AfficherControlAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Controles');
      $Control = $repository->find($id);
     
          $encoders = array( new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);

          $jsonContent = $serializer->serialize($Control, 'json');
          return new Response($jsonContent);

          
    }

     /**
     * @Route("/ModifControle", name="ModifControle")
     */

    public function ModifControlAction( Request $request)
    {
        
        
        $IdControl=$request->get('IdControle');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Controles');
        $controle = $repository->find($IdControl);
        $controle->setLabel($request->get('LabelModal'));
        $controle->setType($request->get('TypeModal'));
        $controle->setPave($request->get('PaveModal'));
        $em->flush();
      
        return $this->redirectToRoute('ListeControles');

          
    }

     /**
      * @Route("/DeleteControle/{id}/", name="DeleteControle")
      */

      public function DeleteControlAction(Request $request, $id)
      {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:Controles');
          $contole = $repository->find($id);

          $repository = $em->getRepository('AppBundle:Actions');
          $actions = $repository->findBy(array('controle'=>$contole));

          foreach($actions as $action)
                    {
                        $em->remove($action);
                        $em->flush();
                    }
          $em->remove($contole);
          $em->flush();
  
          return $this->redirectToRoute('ListeControles');
      }


      /**
     * @Route("/ListeUsers", name="ListeUsers")
     */
    public function ListeUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        
        

        return $this->render('Utilisateurs/ListeUtilisateurs.html.twig', array( 'users'=> $users ));
    }

     /**
      * @Route("/DeleteUser/{id}/", name="DeleteUser")
      */

      public function DeleteUserAction(Request $request, $id)
      {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:User');
          $user = $repository->find($id);
          $em->remove($user);
          $em->flush();
  
          return $this->redirectToRoute('ListeUsers');
      }
  
      /**
       * @Route("/AfficherUser/{id}/", name="AfficherUser")
       * @Method("GET")
       */
  
        public function AfficherUserAction(Request $request, $id)
        {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:User');
          $user = $repository->find($id);
         
              $encoders = array( new JsonEncoder());
              $normalizers = array(new ObjectNormalizer());
  
              $serializer = new Serializer($normalizers, $encoders);
  
              $jsonContent = $serializer->serialize($user, 'json');
              return new Response($jsonContent);
  
              
        }
  
      /**
       * @Route("/ModifUser", name="ModifUser")
       */
  
      public function ModifUserAction( Request $request)
      {
          
          
          $IdUser=$request->get('IdUser');
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:User');
          $user = $repository->find($IdUser);
          $user->setCivilite($request->get('Civilite'));
          $user->setNom($request->get('Nom'));
          $user->setPrenom($request->get('Prenom'));
          $user->setEmail($request->get('Email'));
          $user->setTel($request->get('Tel'));
          $user->setAdresse($request->get('Adresse'));
          $dateNaissance = new \DateTime($request->get('DateNaissance'));
          $user->setDateNaissance($dateNaissance);
          $user->setRole($request->get('Role'));
          $em->flush();
        
          return $this->redirectToRoute('ListeUsers');
  
            
      }

      //=============services============================

      
     /**
     * @Route("/ListeServices", name="ListeServices")
     */
    public function ListeServicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository('AppBundle:Services')->findAll();
        $chefs = $em->getRepository('AppBundle:User')->findBy( array('Role'=>'ROLE_SERVICE'));
        //$chefs = $em->getRepository('AppBundle:User')->findAll();
        $departements = $em->getRepository('AppBundle:Departements')->findAll();
        
        return $this->render('Parametres/ListeServices.html.twig', array( 'services'=>$services , 'departements'=> $departements , 'chefs' => $chefs));
    }

    

    /**
     * @Route("/AddService", name="AddService")
     */

    public function AddServiceAction( Request $request)
    {
        
        
        $em = $this->getDoctrine()->getManager();
        $service = new Services();
        $service->setService($request->get('Service'));
        $departement = $em->getRepository('AppBundle:Departements')->find($request->get('Departement'));
        $service->setDepartement($departement);
        $chef = $em->getRepository('AppBundle:User')->find($request->get('Chef'));
        $service->setChef($chef);
        
        $em->persist($service);
        $em->flush();
      
        return $this->redirectToRoute('ListeServices');

          
    }

    /**
     * @Route("/AfficherService/{id}/", name="AfficherService")
     * @Method("GET")
     */

    public function AfficherServiceAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Services');
      $service = $repository->find($id);
     
          $encoders = array( new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);

          $jsonContent = $serializer->serialize($service, 'json');
          return new Response($jsonContent);

          
    }

     /**
     * @Route("/ModifService", name="ModifService")
     */

    public function ModifServiceAction( Request $request)
    {
        
        
        $IdService=$request->get('IdService');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Services');
        $service = $repository->find($IdService);
        $service->setService($request->get('ServiceModal'));
        $departement = $em->getRepository('AppBundle:Departements')->find($request->get('DepartementModal'));
        $service->setDepartement($departement);
        $chef = $em->getRepository('AppBundle:User')->find($request->get('ChefModal'));
        $service->setChef($chef);
        $em->flush();
      
        return $this->redirectToRoute('ListeServices');

          
    }

     /**
      * @Route("/DeleteService/{id}/", name="DeleteService")
      */

      public function DeleteServiceAction(Request $request, $id)
      {
          $em = $this->getDoctrine()->getManager();
          $repository = $em->getRepository('AppBundle:Services');
          $service = $repository->find($id);
          $em->remove($service);
          $em->flush();
  
          return $this->redirectToRoute('ListeServices');
      }

    /**
     * @Route("/ChargerServices/{id}/", name="ChargerServices")
     * @Method("GET")
     */

    public function ChargerServicesAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Services');
      $services = $repository->findBy(array('departement'=>$id));
          $encoders = array( new JsonEncoder());
          $normalizers = array(new ObjectNormalizer());

          $serializer = new Serializer($normalizers, $encoders);

          $jsonContent = $serializer->serialize($services, 'json');
          return new Response($jsonContent);

          
    }

    


    
}