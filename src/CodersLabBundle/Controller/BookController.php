<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookController
 *
 * @Route("/book")
 */
class BookController extends Controller
{
    /**
     * @Route("/add")
     * @Template("CodersLabBundle:Book:add.html.twig")
     */
    public function addBookAction(){
        //przygotowuje EM i pobieram wszytkich autorów aby ich wybierać z listy w formularzu
        $em     = $this->getDoctrine()->getManager();
        $authors = $em->getRepository('CodersLabBundle:Author')->findAll();
        return array('authors'=>$authors);
    }

    /**
     * @param Request $request
     * @Route("/create", name="book_create")
     * @Method("POST")
     */
    public function createBookAction(Request $request){

        if (!$request->request){
            return new Response("Błąd!!!");
        }

        //pobieram dane z POST'a
        $title      = $request->request->get('title');
        $authorId   = $request->request->get('author_id');
        $rating     = $request->request->get('rating');

        //przygotowuje EM i pobieram encję autora
        $em     = $this->getDoctrine()->getManager();
        $author = $em->getRepository('CodersLabBundle:Author')->find($authorId);

        //tworzę ksiażkę
        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setRating($rating);

        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('book_list');
    }

    /**
     * @Route("/list",name = "book_list")
     * @Template("CodersLabBundle:Book:list.html.twig")
     */
    public function bookListAction(){
        $em     = $this->getDoctrine()->getManager();
        $books  = $em->getRepository('CodersLabBundle:Book')->findAll();

        return array('books'=>$books);
    }

    /**
     * @Route("/show/{id}", name="book_show")
     * @Template("CodersLabBundle:Book:show.html.twig")
     */
    public function bookShowAction($id){
        $em     = $this->getDoctrine()->getManager();
        $book   = $em->getRepository('CodersLabBundle:Book')->find($id);

        return array('book'=>$book);
    }


}
