<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorController
 *
 * @Route("/author")
 */
class AuthorController extends Controller
{
    /**
     * @Route("/add")
     * @Template("CodersLabBundle:Author:add.html.twig")
     */
    public function addAuthorAction(){
    }

    /**
     * @param Request $request
     * @Route("/create", name="author_create")
     * @Method("POST")
     */
    public function createAuthorAction(Request $request){

        if (!$request->request){
            return new Response("Błąd!!!");
        }

        //pobieram dane z POST'a
        $name           = $request->request->get('name');
        $description    = $request->request->get('description');

        $em = $this->getDoctrine()->getManager();

        //tworzę autora
        $author = new Author();
        $author->setName($name);
        $author->setDescription($description);

        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('author_list');
    }

    /**
     * @Route("/list",name = "author_list")
     * @Template("CodersLabBundle:Author:list.html.twig")
     */
    public function authorListAction(){
        $em      = $this->getDoctrine()->getManager();
        $authors = $em->getRepository('CodersLabBundle:Author')->findAll();

        return array('authors'=>$authors);
    }

    /**
     * @Route("/show/{id}", name="author_show")
     * @Template("CodersLabBundle:Author:show.html.twig")
     */
    public function authorShowAction($id){
        $em     = $this->getDoctrine()->getManager();
        $author = $em->getRepository('CodersLabBundle:Author')->find($id);

        return array('author'=>$author);
    }

}
