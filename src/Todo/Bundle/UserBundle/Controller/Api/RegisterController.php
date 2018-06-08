<?php
namespace Todo\Bundle\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Todo\Bundle\UserBundle\Entity\User;

class RegisterController extends FOSRestController implements ClassResourceInterface
{
    /**
     *  Create new User account
     * @ApiDoc(
     *     resource=true,
     *     section="User"
     * )
     * @Rest\Post("/register")
     * @Rest\QueryParam(name="username", description="Username")
     * @Rest\QueryParam(name="password", description="Password")
     *
     * @param Request $request
     * @return mixed
     */
    public function registerAction(Request $request){

        $formFactory = $this->get('fos_user.registration.form.factory');

        $userManager = $this->get('fos_user.user_manager');

        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();

        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);


        if (null !== $event->getResponse()){
            return $event->getResponse();
        }

        $form = $formFactory->createForm([
            'csrf_protection'    => false
        ]);
        $form->setData($user);
        $form->submit($request->request->all());

        if (! $form->isValid()) {

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE);

            if (null !== $response = $event->getResponse()){
                return $response;
            }
            return $form;
        }

        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
        if ($event->getResponse()) {
            return $event->getResponse();
        }
        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('geostorm_api_create_new_user');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
        return $response;
    }
}