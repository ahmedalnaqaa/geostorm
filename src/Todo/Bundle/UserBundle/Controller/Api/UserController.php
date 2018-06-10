<?php

namespace Todo\Bundle\UserBundle\Controller\Api;


use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/register")
     * @Method("POST")
     */

    public function registerAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $username = $request->get('username');

        $password = $request->get('password');

        $encoded = $encoder->encodePassword($user, $password);

        $user->setUsername($username);

        $user->setPassword($encoded);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);

        $entityManager->flush();

        return new JsonResponse("User Created Successfully",Response::HTTP_OK);
    }

}