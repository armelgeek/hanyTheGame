<?php


namespace App\Controller\Api;


use App\Repository\QuestionRepository;

class TotalCountCategoryController
{
   
   private $repo;
   function __construct(QuestionRepository $repo)
   {
     $this->repo = $repo;
   }
    public function __invoke($data,$id)
    {
        
        return $this->repo->findCountByCategory();
    }
}