<?php


namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("mg/game/admin/category", name="category_")
 */
class CategoryController extends CrudController
{
    protected $searchField="nom";
    protected $routePrefix="category";
    protected $entity="categorie";
    /**
     * @Route("/new", name="new", methods={"POST", "GET"})
     * @return Response
     */
    public function new():Response
    {
        return $this->crudNew(new Category());
    }
    /**
     * @Route("/", name="index", methods={"POST", "GET"})
     */
    public  function index(){
        return $this->crudIndex(new Category());
    }

    /**
     * @Route("/{id}/afficher", name="show", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category):Response
    {
        return $this->crudShow($category);
    }

    /**
     * @Route("/{id}", name="edit", methods={"GET","POST"})
     * @param Category $category
     * @return Response
     */
    public function edit(Category $category):Response
    {
        return $this->crudEdit($category);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param Category $category
     * @return Response
     */
    public function delete(Category $category): Response
    {
        return $this->crudDelete($category);
    }

    /**
     * @Route("/{id}/clone", name="clone", methods={"GET","POST"})
     * @param Category $category
     * @return Response
     */
    public function clone(Category $category): Response
    {
        return $this->crudClone($category);
    }

}