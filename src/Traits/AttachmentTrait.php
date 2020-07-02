<?php
/**
 * Copyright (c) $today.year.Miray Geek.
 */

namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
trait AttachmentTrait
{

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var int|null
     */
    private $imageSize;


    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName()
    {
        return $this->imageName;
    }
    
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize()
    {
        return $this->imageSize;
    }


}
