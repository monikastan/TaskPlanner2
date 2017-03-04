<?php

namespace TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use TaskBundle\Entity\Task;
use TaskBundle\Entity\User;
use TaskBundle\Entity\Priority;
use TaskBundle\Entity\Status;


class MainController extends Controller
{
    
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function mainAction()
    {
        return $this->redirectToRoute('task_index');
    }
        
    
    
}
