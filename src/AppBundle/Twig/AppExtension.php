<?php
// Note that the namespace must match with
// your project !
namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AppExtension extends \Twig_Extension

{    
    
    public function getFunctions()
    {
        // Register the function in twig :
        // In your template you can use it as : {{find(123)}}
        return array(
            new \Twig_SimpleFunction('GetNotifications', array($this, 'GetNotifications')),
            new \Twig_SimpleFunction('GetNotificationsActif', array($this, 'GetNotificationsActif')),
            new \Twig_SimpleFunction('ConsulteNotif', array($this, 'ConsulteNotif')),
            new \Twig_SimpleFunction('GetStatutNotif', array($this, 'GetStatutNotif')),
           
        );
    }
    
    protected $doctrine;
    // Retrieve doctrine from the constructor
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function GetNotifications($IdUser){

        $notifications = $this->doctrine->getManager()->getRepository('AppBundle:Notifications')->findBy(array('user'=> $IdUser), array('id'=>'desc'));
        return  $notifications;
    }
    public function GetNotificationsActif($IdUser){
        
        $notificationsActif = $this->doctrine->getManager()->getRepository('AppBundle:Notifications')->findBy(array('user'=> $IdUser , 'statut'=> 1));
        return  $notificationsActif;
    }
    
    public function  ConsulteNotif($IdUser,$IdArrivee){
        $notif = $this->doctrine->getManager()->getRepository('AppBundle:Notifications')->findOneBy(array('user'=> $IdUser , 'arrivee'=> $IdArrivee));
        if ( count($notif) > 0){
        $notif->setStatut(0);
        $this->doctrine->getManager()->flush();
        }

    }

    public function  GetStatutNotif($IdUser,$IdArrivee){
        $notif = $this->doctrine->getManager()->getRepository('AppBundle:Notifications')->findOneBy(array('user'=> $IdUser , 'arrivee'=> $IdArrivee));
        if ( count($notif) == 0){
            return 0;
        }else {
            return  $notif->getStatut();
        }
        
        

    }
}