<?php
/**
 * Copyright (c) $today.year.Miray Geek.
 */

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity()
 */
trait TimingTrait
{
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    public  $createdAt;
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTimeInterface|null
     */
    public $updatedAt;
    public function __construct(){
        $this->createdAt=new \DateTime();
        $this->updatedAt=new \DateTime();
    }
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt ?: new \DateTime();
    }
    /**
     * @param \DateTimeInterface|null $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTimeInterface|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}