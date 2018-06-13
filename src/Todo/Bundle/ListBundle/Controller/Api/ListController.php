<?php

namespace Todo\Bundle\ListBundle\Controller\Api;

use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Todo\Bundle\ListBundle\Entity\ListItem;
use FOS\RestBundle\Controller\Annotations as Rest;
use Todo\Bundle\ListBundle\Form\ListType;
use Todo\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="list_controller")
 */
class ListController extends FOSRestController
{
    /**
     * @Rest\Get("/api/list")
     *
     * @ApiDoc(
     *  section="List",
     *  description="Returns User Lists"
     * )
     */
    public function getListsAction(User $user)
    {
        if (!$this->getUser()) {
            throw new AccessDeniedHttpException("Access Denied");
        }
        $em = $this->getDoctrine()->getManager();

        $lists = $em->getRepository('TodoBundleListBundle:ListItem')->getUserLists($user);

        return array(
           'lists' => $lists
        );
    }

    /**
     * Get Items of Specific List.
     *
     * @param ListItem $listItem
     * @return JsonResponse
     *
     * @Rest\Get("/api/list/{id}")
     *
     * @ApiDoc(
     *   resource=true,
     *   section="List"
     * )
     */
    public function getListItems(ListItem $list)
    {
       if (!$this->getUser() || $list->getUser() != $this->getUser()) {
            throw new AccessDeniedHttpException("Access Denied");
       }
       $items = $list->getItems();

       $data = $this->get('jms_serializer')->serialize($items,'json');

       return new JsonResponse($data, Response::HTTP_ACCEPTED);
    }

    /**
     * Create List.
     *
     * @Rest\Post("/api/list/create")
     * @Rest\View(serializerGroups={"Details", "Default"})
     * @ApiDoc(
     *     resource=true,
     *     section="List",
     *     input="\Todo\Bundle\ListBundle\Form\ListType"
     * )
     * @param Request $request
     * @return mixed
     */
    public function createListAction(Request $request)
    {
        if (!$this->getUser()) {
            throw new AccessDeniedHttpException("Access Denied");
        }
        $em = $this->getDoctrine()->getManager();

        $list = new ListItem();

        $form = $this->createForm(ListType::class, $list, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()){
            $list->setUser($this->getUser());
            $em->persist($list);
            $em->flush();
            return $list;
        }
        return $form;
    }

    /**
     * Edit List.
     *
     * @Rest\Post("/api/list/{id}/edit")
     * @Rest\View(serializerGroups={"Details", "Default"})
     * @ApiDoc(
     *   resource=true,
     *   section="List",
     *   input="\Todo\Bundle\ListBundle\Form\ListType"
     * )
     *
     * @param Request $request
     * @param ListItem $list
     * @return mixed
     */
    public function editListAction(Request $request,ListItem $list)
    {
        if (!$this->getUser() || $this->getUser() != $list->getUser()) {
            throw new AccessDeniedHttpException("Access Denied");
        }
        $em = $this->getDoctrine()->getManager();

        if (!$list){
            return $this->createNotFoundException("List is not found in the Database");
        }
        $form = $this->createForm(ListType::class, $list, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($form->isValid()){
            $em->persist($list);
            $em->flush();
            return $list;
        }
        return $form;
    }

    /**
     * Delete List.
     *
     * @Rest\Delete("/api/list/{id}/delete")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "List"
     * )
     */
    public function deleteListAction(ListItem $list)
    {
        if ($list->getUser() != $this->getUser()){
            throw new AccessDeniedHttpException("Access Denied");
        }
        $em = $this->getDoctrine()->getManager();
        if (!$list){
            return $this->createNotFoundException("List is not Found");
        }
        $em->remove($list);
        $em->flush();

        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }
}
