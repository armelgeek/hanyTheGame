<?php
namespace App\Helper\ImageUpload;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
class UrlGenerator
{

    private  $helper;

    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
    }

    public function generate(?object $attachment): ?string
    {
        if ($attachment === null) {
            return null;
        }
        return $this->helper->asset($attachment);
    }

}
