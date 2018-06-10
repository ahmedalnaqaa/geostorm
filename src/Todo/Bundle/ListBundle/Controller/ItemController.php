<?php

namespace Todo\Bundle\ListBundle\Controller;

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
     * @param Request $request
     * @param ListItem $listItem
     * @Rest\Post("/api/list/{id}/item")
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

        $item->setContent($content);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($item);

        $entityManager->flush();

        return new JsonResponse("Item is Created", Response::HTTP_OK);

    }


    /**
     * @param Request $request
     * @param $list_id
     * @param $id
     * @return JsonResponse
     * @Rest\Post("/api/list/{list_id}/item/{id}/edit")
     */
    public function updateItem(Request $request, $list_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('AppBundle:ListItem')->find($list_id);

        $entityManger = $this->getDoctrine()->getManager();

        $item = $entityManger->getRepository('AppBundle:Item')->find($id);

        if (!$list){
            return new JsonResponse(['message' => 'List is not Found in the Database'], Response::HTTP_NOT_FOUND);
        }

        $title = $request->get('title');

        $item->setTitle($title);

        $entityManger->flush();

        return new JsonResponse("Item is Updated Successfully",Response::HTTP_OK);

    }


    /**
     * @Rest\Delete("/api/list/{list_id}/item/{id}/delete")
     */

    public function deleteItemInList(ListItem $listItem,$id)
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