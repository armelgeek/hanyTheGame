<?php

namespace App\Helper\ImageUpload;

use Intervention\Image\ImageManager;

class ImageResizer
{

    private  $urlGenerator;
    public function __construct(UrlGenerator $urlGenerator)
    {
      $this->urlGenerator = $urlGenerator;
    }

    public function resize(object $obj, ?int $width = null, ?int $height = null): void
    {
       $manager = new ImageManager(['driver' => 'gd']);
       $manager->make($this->urlGenerator->generate($obj))->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
       })->save($this->urlGenerator->generate($obj));
    }

}
