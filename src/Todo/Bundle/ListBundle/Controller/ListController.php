<?php

namespace Todo\Bundle\ListBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Bundle\ListBundle\Entity\ListItem;
use FOS\RestBundle\Controller\Annotations as Rest;
use Todo\Bundle\ListBundle\Form\ListType;

class ListController extends Controller
{
    /**
     * @Rest\Get("/api/list")
     *
     * @ApiDoc(
     *  section="List",
     *  description="Returns all Lists"
     * )
     */
    public function getListsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lists = $em->getRepository('TodoBundleListBundle:ListItem')->findAll();

        return new JsonResponse($lists, Response::HTTP_ACCEPTED);
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
    public function getListItems(ListItem $listItem)
    {
        $items = [];

       foreach ($listItem->getItems() as $item){
           $items[] = [
             'id' => $item->getId(),
             'content' => $item->getContent(),
             'order' => $item->getOrder(),
             'created_at' => $item->getCreatedAt()->format('M d, Y')
           ];
       }

       $data = [
          'items' => $items
       ];

        return new JsonResponse($data ,Response::HTTP_OK);
    }

    /**
     * Create List.
     *
     * @Rest\Post("/api/list/create")
     * @Rest\QueryParam(name="title", description="List Title")
     * @Rest\QueryParam(name="description", description="List Description")
     * @Rest\QueryParam(name="created_at", description="List CreatedAt")
     * @Rest\View(serializerGroups={"Details", "Default"})
     * @ApiDoc(
     *     resource=true,
     *     section="List",
     * )
     * @param Request $request
     */
    public function createListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $list = new ListItem();

        $form = $this->createForm(ListType::class, $list, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()){
            $list = $form->getData();
            $em->persist($list);
            $em->flush();
            return $list;
        }
        return $form;
    }

    /**
     * Update List.
     *
     * @Rest\Post("/api/list/{id}/edit")
     * @Rest\QueryParam(name="title", description="List Title")
     * @Rest\QueryParam(name="description", description="List Description")
     * @Rest\QueryParam(name="created_at", description="List CreatedAt")
     *
     * @ApiDoc(
     *   resource=true,
     *   section="List"
     * )
     *
     * @param Request $request
     * @param ListItem $listItem
     */
    public function editListAction(Request $request,ListItem $listItem)
    {

        $em = $this->getDoctrine()->getManager();

        if (!$listItem){
            return $this->createNotFoundException("List is not found in the Database");
        }

        $title = $request->get('title');
        $description = $request->get('description');
        $created_at = $request->get('created_at');

        $listItem->setTitle($title);
        $listItem->setDescription($description);
        $listItem->setCreatedAt($created_at);

        $em->persist($listItem);
        $em->flush();

        return new JsonResponse("List Updated Successfully", Response::HTTP_OK);
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
    public function deleteListAction(ListItem $listItem)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$listItem){
            return $this->createNotFoundException("List is not Found");
        }
        $em->remove($listItem);
        $em->flush();

        return new JsonResponse("List is Deleted",Response::HTTP_NO_CONTENT);
    }
}
