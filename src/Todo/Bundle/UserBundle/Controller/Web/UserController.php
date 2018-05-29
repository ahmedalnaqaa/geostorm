<?php

namespace Todo\Bundle\UserBundle\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    /**
     * @Route("/", name="todo_user_main")
     * @Template()
     */
    public function indexAction()
    {
        return;
    }

}