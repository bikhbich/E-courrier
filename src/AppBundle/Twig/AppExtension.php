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
            new \Twig_SimpleFunction('find', array($this, 'find')),
        );
    }
    
    protected $doctrine;
    // Retrieve doctrine from the constructor
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function find($id){
        //$em = $this->doctrine->getManager();
        $myRepo = $this->em->getRepository('AppBundle:User');
        ///

        return $myRepo->find($id)->getNom();
    }
    
    public function getName()
    {
        return 'Twig myCustomName Extensions';
    }
}