<?php

namespace Todo\Bundle\ListBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Todo\Bundle\ListBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Todo\Bundle\ListBundle\Entity\ListItem;

class ItemController extends Controller
{
    /**
     * Create new Item in specific List
     *
     * @Rest\Post("/api/list/{id}/item")
     * @Rest\QueryParam(name="content", description="Item Content")
     * @Rest\QueryParam(name="order", description="Item order")
     * @Rest\QueryParam(name="created_at", description="List CreatedAt")
     *
     * @ApiDoc(
     *     section="Item"
     * )
     *
     * @param Request $request
     * @param ListItem $listItem
     */
    public function addItemAction(Request $request,ListItem $listItem)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('TodoBundleListBundle:ListItem')->findBy(
            array('listItem' => $listItem)
        );

        if (!$list){
            return new JsonResponse(['message' => 'List is not Found in the Database'], Response::HTTP_NOT_FOUND);
        }
        $item = new Item();

        $content = $request->get('content');
        $order   = $request->get('order');
        $created_at = $request->get('created_at');

        $item->setContent($content);
        $item->setOrder($order);
        $item->setCreatedAt($created_at);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($item);
        $entityManager->flush();

        return new JsonResponse("Item is Created", Response::HTTP_OK);
    }

    /**
     * Update Item
     *
     * @Rest\Post("/api/list/{list_id}/item/{id}/edit")
     * @Rest\QueryParam(name="content", description="Item Content")
     * @Rest\QueryParam(name="order", description="Item Order")
     * @Rest\QueryParam(name="created_at", description="Item CreatedAt")
     *
     * @ApiDoc(
     *     section="Item",
     * )
     * @param Request $request
     * @param $list_id
     * @param $id
     * @return JsonResponse
     */
    public function updateItemAction(Request $request, $list_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('TodoBundleListBundle:ListItem')->find($list_id);

        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository('TodoBundleListBundle:Item')->find($id);

        if (!$list){
            return new JsonResponse(['message' => 'List is not Found in the Database'], Response::HTTP_NOT_FOUND);
        }

        $content = $request->get('content');
        $order   = $request->get('order');
        $created_at = $request->get('created_at');

        $item->setContent($content);
        $item->setOrder($order);
        $item->setCreatedAt($created_at);

        $entityManager->persist($item);
        $entityManager->flush();

        return new JsonResponse("Item is Updated Successfully",Response::HTTP_OK);

    }

    /**
     * Delete Item
     *
     * @Rest\Delete("/api/list/{list_id}/item/{id}/delete")
     *
     * @ApiDoc(
     *     section="Item"
     * )
     */
    public function deleteItemAction(ListItem $listItem,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('TodoBundleListBundle:ListItem')->findBy(
            array('listItem' => $listItem)
        );

        $entityManger = $this->getDoctrine()->getManager();

        $item = $entityManger->getRepository('TodoBundleListBundle:Item')->find($id);

        if (!$list){
            return new JsonResponse("List is not Found",Response::HTTP_NOT_FOUND);
        }
        elseif (!$item){
            return new JsonResponse("Item is not Found",Response::HTTP_NOT_FOUND);
        }

        $em->remove($item);
        $em->flush();

        return new JsonResponse("Item ID is Deleted",Response::HTTP_OK);
    }
}