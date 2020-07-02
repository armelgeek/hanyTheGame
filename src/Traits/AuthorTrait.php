<?php

namespace App\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
trait AuthorTrait
{
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author = null;

    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        return $this;
    }

}