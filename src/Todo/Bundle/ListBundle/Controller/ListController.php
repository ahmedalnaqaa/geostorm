<?php

namespace Todo\Bundle\ListBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Bundle\ListBundle\Entity\ListItem;


class ListController extends Controller
{


    /**
     * @Route("/api/list")
     * @Method("GET")
     * @ApiDoc(
     *  description="This is a description of your API method",
     *  views = { "default", "premium" }
     * )
     */

    public function index()
    {

        $lists = $this->getDoctrine()->getRepository('TodoBundleListBundle:ListItem');

        return new JsonResponse($lists);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/api/list/{id}")
     * @Method("GET")
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
     * @Route("/api/list/create")
     * @Method("POST")
     */
    public function postList(Request $request)
    {

        $list = new ListItem();

        $validator = $this->get('validator');

        $errors = $validator->validate($list);

        if (count($errors) > 0) {

            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $title = $request->get('title');

        $list->setTitle($title);

        $em = $this->getDoctrine()->getManager();
        $em->persist($list);
        $em->flush();

        return new JsonResponse('List is Created',Response::HTTP_OK);
    }

    /**
     * @Route("/api/list/{id}/edit")
     * @Method("PUT")
     */
    public function updateList(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('TodoBundleListBundle:ListItem')->find($id);

        if (!$list){
            return new JsonResponse("List is not found in the Database",Response::HTTP_NOT_FOUND);
        }

        $title = $request->get('title');

        $list->setTitle($title);

        $em->flush();

        return new JsonResponse("List Updated Successfully", Response::HTTP_OK);

    }

    /**
     * @param $id
     * @Route("/api/list/{id}/delete")
     * @Method("Delete")
     */
    public function deleteList($id)
    {
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository('TodoBundleListBundle:ListItem')->find($id);

        if (!$list){
            return new JsonResponse("List is not Found",Response::HTTP_NOT_FOUND);
        }
        $em->remove($list);

        $em->flush();

        return new JsonResponse("List ID ".$id."is Deleted",Response::HTTP_OK);
    }
}
