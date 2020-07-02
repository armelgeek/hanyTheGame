<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Annotations\Crudding;
use App\Annotations\CrudField;
use App\Annotations\CrudListField;
use App\Annotations\CrudFieldSortBy;
use App\Annotations\CrudFieldShow;
use App\Traits\TimingTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 *     paginationItemsPerPage=10,
 *     normalizationContext={"groups"={"read:category"}},
 *     collectionOperations={"GET"},
 *     itemOperations={
 *     "get"={
 *          "normalization_context"={"groups"={"read:category","read:full:category"}}
 *     }
 * }
 * )
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Crudding()
 * @UniqueEntity(
 *     fields={"nom"},
 *     message="La catégorie existe dèja."
 * )
 */
class Category
{
    use TimingTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @CrudField(type="string",label="Nom du categorie")
     * @CrudListField(field="nom",label="Nom du categorie")
     * @CrudFieldSortBy()
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Groups({"read:question"})
     * @Groups({"read:category"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="category")
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

   
    public function __toString()
    {
        return $this->nom;
    }
    
    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setCategory($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getCategory() === $this) {
                $question->setCategory(null);
            }
        }

        return $this;
    }
}
