<?php
namespace Todo\Bundle\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Todo\Bundle\UserBundle\Form\Type\RegisterationType;

class UserController extends FOSRestController
{
    /**
     * Create new User account
     * @ApiDoc(
     *     resource=true,
     *     section="User",
     *     input="Todo\Bundle\UserBundle\Form\Type\RegisterationType"
     * )
     * @Rest\Post("/api/v2/user/register", name="geostorm_api_create_new_user")
     * @Rest\View(serializerGroups={"Default","Details"})
     * @Rest\QueryParam(name="username", description="Username")
     * @Rest\QueryParam(name="password", description="Password")
     *
     * @param Request $request
     * @return mixed
     */
    public function registerAction(Request $request){

        $userManager = $this->container->get('fos_user.user_manager');
        $dispatcher = $this->container->get('event_dispatcher');
        $user = $userManager->createUser();
        $form = $this->createForm(RegisterationType::class, $user, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);


        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user->setEnabled(true);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, new FormEvent($form, $request));

            $userManager->updateUser($user);
            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('geostorm_api_create_new_user');
                $response = new RedirectResponse($url);
            }
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
            return array('user' => $user);
        }
        return $form;
    }
}