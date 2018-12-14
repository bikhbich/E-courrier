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


            return $this->redirectToRoute('ListeArrivee');
        }

        // $lastArrivee = $em->getRepository('AppBundle:Arrivee')->findOneBy(array(), array('id' => 'DESC'));
        // var_dump($lastArrivee->getId());die;
        return $this->render('Arrivees/ajouterArrivee.html.twig', [
            'formArrivee' => $formArrivee->createView(),
        ]);
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
        $arrivees = $em->getRepository('AppBundle:Arrivee')->findAll();
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
}