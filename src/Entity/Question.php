<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Annotations\Crudding;
use App\Annotations\CrudField;
use App\Annotations\CrudListField;
use App\Annotations\CrudFieldSortBy;
use App\Annotations\CrudFieldShow;
use App\Traits\TimingTrait;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Controller\Api\QuestionParCategorieController;
use App\Controller\Api\TotalCountCategoryController;
use App\Controller\Api\GetRandomQuestionController;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read:question"}},
 *     denormalizationContext={"groups"={"write"}},
 *     collectionOperations={"get","post"},
 *     itemOperations={
 *     "get"={
 *          "normalization_context"={"groups"={"read:question","read:full:question"}}
 *     },
 *     "question_par_category"={
 *         "method"="GET",
 *         "path"="/question-par-category/{id}",
 *         "controller"=QuestionParCategorieController::class
 *     },
 *     "total_par_category"={
 *         "method"="GET",
 *         "path"="/total-par-category/{id}",
 *         "controller"=TotalCountCategoryController::class
 *     },
 *     "get_random_question"={
 *         "method"="GET",
 *         "path"="/get-random-question/{id}",
 *         "controller"=GetRandomQuestionController::class
 *     },
 
       "put"
 * }
 * )
 * @ApiFilter(SearchFilter::class,properties={"category":"exact"})
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @Crudding()
 */
class Question
{
    use TimingTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:question","write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=1,max=255)
     * @Groups({"read:question","write"})
     * @CrudField(type="textarea",label="Question 1")
     * @CrudListField(field="choix1",label="Question 1")
     * @CrudFieldShow()
     */
    private $choix1;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Groups({"read:question","write"})
     * @CrudFieldShow()
     */
    private $vote1=0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=1,max=255)
     * @Groups({"read:question","write"})
     * @CrudField(type="textarea",label="Question 2")
     * @CrudListField(field="choix2",label="Question 2")
     */
    private $choix2;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Groups({"read:question","write"})
     */
    private $vote2=0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read:question","write"})
     */
    private $nb_signal=0;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="questions")
     * @Groups({"read:question","write"})
     * @CrudField(type="entity",label="Categorie de l'article",entity={ App\Entity\Category::class,"nom"})
     */

    private $category;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"read:question","write"})
     */
    private $pseudo;
    /**
    * Gedmo\Mapping\Annotation\Sortable()
    * @ORM\Column(name="position", type="integer")
    */
    private $position;
    public function getPosition(){
        return $this->position;
    }
    public function setPosition($position){
        $this->position = $position;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoix1(): ?string
    {
        return $this->choix1;
    }

    public function setChoix1(string $choix1): self
    {
        $this->choix1 = $choix1;

        return $this;
    }

    public function getVote1(): ?string
    {
        return $this->vote1;
    }

    public function setVote1(?string $vote1): self
    {
        $this->vote1 = $vote1;

        return $this;
    }

    public function getChoix2(): ?string
    {
        return $this->choix2;
    }

    public function setChoix2(string $choix2): self
    {
        $this->choix2 = $choix2;

        return $this;
    }

    public function getVote2(): ?string
    {
        return $this->vote2;
    }

    public function setVote2(?string $vote2): self
    {
        $this->vote2 = $vote2;

        return $this;
    }

    public function getNbSignal(): ?int
    {
        return $this->nb_signal;
    }

    public function setNbSignal(?int $nb_signal): self
    {
        $this->nb_signal = $nb_signal;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }
}
