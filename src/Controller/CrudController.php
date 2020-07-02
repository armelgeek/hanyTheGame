<?php

namespace App\Controller;

use App\Annotations\CrudReader;
use App\Entity\Post;
use App\Form\AutomaticForm;
use App\Helper\ImageUpload\ImageResizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Helper\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template E
 */
abstract class CrudController extends AbstractController
{
    private $annotationReader;
    protected $entity="contenu";
    protected $searchField="nom";
    protected $selection="not";
    protected $routePrefix="";
    protected  $em;
    protected  $paginator;
    private  $dispatcher;
    private  $requestStack;
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        EventDispatcherInterface $dispatcher,
        RequestStack $requestStack,
        CrudReader $annotationReader,
        ImageResizer $resizer
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->dispatcher = $dispatcher;
        $this->requestStack = $requestStack;
        $this->annotationReader = $annotationReader;
        $this->resizer = $resizer;
    }
    public function listSort($entityClass,$id, $position): Response{
        $em = $this->getDoctrine()->getManager();
        $row =$this->getRepository(get_class($entityClass))->find($id);
        $row->setPosition($position);
        $this->em->persist($row);
        $this->em->flush();
        $this->addFlash('success', 'Réorganisation reussie');
        return $this->redirectToRoute($this->routePrefix. '_index');
    }
    public function crudNew(object $data): Response
    {

        $request = $this->requestStack->getCurrentRequest();
        $form = $this->createForm(AutomaticForm::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $data->createdAt=new \DateTime();
          //  $data->author=$this->getUser();
            $data->updatedAt=new \DateTime();

            $this->em->persist($data);
            $this->em->flush();
          //   $this->resizer->resize($data->,200,200);
            $this->addFlash('success', 'Le contenu a bien été créé');
            return $this->redirectToRoute($this->routePrefix. '_index');
        }

        return $this->render("admin/crud/new.html.twig", [
                'form' => $form->createView(),
                'routePrefix'=> $this->routePrefix,
                'title'=> 'Ajouter un(e)'.$this->entity,
                 'selection'=> $this->selection,
        ]);

    }

    public function crudShow(object $data): Response
    {

        $row=$this->annotationReader->getCrudFieldShow($data);
            return $this->render('admin/crud/show.html.twig', [
                'row' => $row,
                'routePrefix'=> $this->routePrefix,
                'data' => $data,
                'title'=> $this->entity.' n°',
                'selection'=> $this->selection,
    
            ]);
    
    }
    
    public function crudIndex($entityClass,QueryBuilder $query = null): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $query = $query ?: $this->getRepository(get_class($entityClass))
            ->createQueryBuilder('row')
            ->orderBy('row.position', 'ASC');
        if ($request->get('q')) {
            $query = $this->applySearch($request->get('q'), $query);
        }
        $thead=$this->annotationReader->getTableFields($entityClass);
        $theadContent=$this->annotationReader->getTableLabelFields($entityClass);
        $tbody=$this->annotationReader->getTableContentFields($entityClass);
        $sort=$this->annotationReader->getCrudFieldSortBy($entityClass);
        $show=$this->annotationReader->getCrudFieldShow($entityClass);
        if(!empty($show)) $showing=true; else $showing=false;
        if(!empty($sort)){
            $tri=true;
            $this->paginator->allowSort($sort);
        }else{
            $tri=false;
        }
        $rows = $this->paginator->paginate($query->getQuery());

        return $this->render("admin/crud/index.html.twig", [
            'rows'   => $rows,
            'tri'=>$tri,
            'sort'=>$sort,
            'showing'=>$showing,
            'thead' => $thead,
            'theadCount' => count($thead)+1,
            'tbody' => $tbody,
            'routePrefix'=> $this->routePrefix,
            'theadContent' => $theadContent,
            'title'=> 'Gestion des '.$this->entity.'s'
        ]);
    }
    public function crudEdit(object $data): Response
    {

        $request = $this->requestStack->getCurrentRequest();
        $form = $this->createForm(AutomaticForm::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //if( isset($data->author)) $data->author=$this->getUser();
            $data->updatedAt=new \DateTime();
            $this->em->flush();
//            $this->resizer->resize($data,200,200);
            $this->addFlash('success', 'Le contenu a bien été modifié');
            return $this->redirectToRoute($this->routePrefix . '_index');

        }

        return $this->render("admin/crud/edit.html.twig", [
            'data' => $data,
            'routePrefix'=> $this->routePrefix,
            'form' => $form->createView(),
            'title'=> 'Editer  un(e)'.$this->entity,
            'selection'=> $this->selection,
        ]);

    }
    public function crudDelete(object $entity)
    {
        $request = $this->requestStack->getCurrentRequest();
        $this->em->remove($entity);
        $this->em->flush();
        $this->addFlash('success', 'Le contenu a bien été supprimé');
        return $this->redirectToRoute($this->routePrefix . '_index');

    }
    public function deleteChildren(object $entity){
       $request = $this->requestStack->getCurrentRequest();
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function crudClone(object $entity): Response
    {
        if(isset($entity->slug)) unset($entity->slug);
        $clone = clone $entity;
        return $this->crudNew($clone);
    }

    public function getRepository($data): \Doctrine\Persistence\ObjectRepository
    {
        return $this->em->getRepository($data);
    }

    protected function applySearch(string $search, QueryBuilder $query): QueryBuilder
    {
        return
            $query->where("LOWER(row.{$this->searchField}) LIKE :search")
            ->setParameter('search', "%" . strtolower($search) . "%");
    }
    private function resizeImage($targetPath)
    {
        $manager = new ImageManager(['driver' => 'gd']);
        $manager->make($targetPath)->widen(768, function ($constraint) {
            $constraint->upsize();
        })->save($targetPath);
    }
}
