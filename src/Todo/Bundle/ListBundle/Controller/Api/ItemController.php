<?php

namespace Todo\Bundle\ListBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Todo\Bundle\ListBundle\Entity\Item;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Todo\Bundle\ListBundle\Entity\ListItem;
use Todo\Bundle\ListBundle\Form\ItemType;

class ItemController extends FOSRestController
{
    /**
     * Create new Item in specific List
     *
     * @Rest\Post("/api/list/{id}/item")
     * @Rest\View(serializerGroups={"Details", "Default"})
     * @ApiDoc(
     *     section="Item",
     *     input="Todo\Bundle\ListBundle\Form\ItemType"
     * )
     *
     * @param Request $request
     * @param ListItem $list
     * @return mixed
     */
    public function addItemAction(Request $request,ListItem $list)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$list){
            throw $this->createNotFoundException('List is not found');
        }
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()){
            $em->persist($item);
            $em->flush();
            return $item;
        }
        return $form;
    }

    /**
     * Edit Item
     *
     * @Rest\POST("/api/item/{id}/edit")
     * @Rest\View(serializerGroups={"Details", "Default"})
     *
     * @ApiDoc(
     *     section="Item",
     *     input="Todo\Bundle\ListBundle\Entity\Item"
     * )
     * @param Request $request
     * @param Item $item
     * @return mixed
     */
    public function editItemAction(Request $request,Item $item)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ItemType::class, $item, array(
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        $form->handleRequest($request);

        if ($form->isValid()){
            $em->persist($item);
            $em->flush();
            return $item;
        }
        return $form;
    }

    /**
     * Delete Item
     *
     * @Rest\Delete("/api/item/{id}/delete")
     *
     * @ApiDoc(
     *     section="Item"
     * )
     */
    public function deleteItemAction(Item $item)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$item){
            throw $this->createNotFoundException('Item is not found');
        }
        $em->remove($item);
        $em->flush();

        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }
}