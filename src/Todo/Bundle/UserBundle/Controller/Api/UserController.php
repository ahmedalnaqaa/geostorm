<?php

namespace Todo\Bundle\UserBundle\Controller\Api;


use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Todo\Bundle\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @Route("/api/login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');


        $user = $this->getDoctrine()->getRepository('User::class')->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        return new JsonResponse("Login Successfully",Response::HTTP_OK);


    }

}