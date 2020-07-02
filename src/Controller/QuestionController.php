<?php


namespace App\Controller;

use App\Entity\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/question", name="question_")
 */
class QuestionController extends CrudController
{
    protected $searchField="choix1";
    protected $routePrefix="question";
    protected $entity="question";
    /**
     * @Route("/sorting/{id}/{position}", name="sorting", methods={"GET"})
     * @return Response
    */
    public function sorting($id,$position){ 
        return $this->listSort(new Question(),$id,$position);
    }
    /**
     * @Route("/new", name="new", methods={"POST", "GET"})
     * @return Response
     */
    public function new():Response
    {
        return $this->crudNew(new Question());
    }
    /**
     * @Route("/", name="index", methods={"POST", "GET"})
     */
    public  function index(){
        return $this->crudIndex(new Question());
    }

    /**
     * @Route("/{id}/afficher", name="show", methods={"GET"})
     * @param Question $question
     * @return Response
     */
    public function show(Question $question):Response
    {
        return $this->crudShow($question);
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"})
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question):Response
    {
        return $this->crudEdit($question);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param Question $question
     * @return Response
     */
    public function delete(Question $question): Response
    {
        return $this->crudDelete($question);
    }

    /**
     * @Route("/{id}/clone", name="clone", methods={"GET","POST"})
     * @param Question $question
     * @return Response
     */
    public function clone(Question $question): Response
    {
        return $this->crudClone($question);
    }

}