<?php

namespace App\Form;
use App\Annotations\CrudReader;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Génère un formulaire de manière automatique en lisant les propriété d'un objet
 */
class AutomaticForm extends AbstractType
{
    private $annotationReader;
    const TYPES = [
        "string"                 => TextType::class,
        "color"                 => ColorType::class,
        "integer"                   => NumberType::class,
        "file"                   => VichImageType::class,
        "vichUploader"           => VichImageType::class,
        "entity"                 => EntityType::class,
        'checkbox'               => CheckboxType::class,
        'textarea'               => TextareaType::class,
        'link'                   => UrlType::class,
        'collection'                   =>CollectionType::class
    ];

    public function __construct(CrudReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'];
        try {
            $refClass = new ReflectionClass($data);
            $classProperties = $refClass->getProperties(\ReflectionProperty::IS_PRIVATE);
            foreach ($classProperties as $property) {

                $name = $property->getName();
                    if ($name!="id") {
                        if($this->annotationReader->isCrudField($data, $name)){
                            if (array_key_exists($this->annotationReader->getOneField($data, $name), self::TYPES)) {
                                if ($this->annotationReader->getOneField($data, $name) == 'entity') {
                                    $builder->add($name, EntityType::class, [
                                        'class' => $this->annotationReader->getOneEntite($data, $name)[0],
                                        'choice_label' => $this->annotationReader->getOneEntite($data, $name)[1],
                               //         'attr' => ['class' => 'col-lg-6']
                                    ]);
                                }
                               else if ($this->annotationReader->getOneField($data, $name) == 'collection') {

                                    $builder->add($name,CollectionType::class, array(
                                        'entry_type'   =>$this->annotationReader->getOneEntite($data, $name)[0],
                                        'allow_add'    => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                               //         'attr' => ['class' => 'col-lg-6']
                                    ));
                                }
                          /*     if($this->annotationReader->getOneField($data, $name)=='collection'){

                                     $builder->add($name,CollectionType::class, array(
                                        'entry_type'   => $this->annotationReader->getOneForm($data, $name),
                                        'allow_add'    => true,
                                        'allow_delete' => true,
                                        'by_reference' => false
                                    ));
                                    }
*/
                            else{
                            $builder->add($name,self::TYPES[$this->annotationReader->getOneField($data, $name)],[
                                'required' =>false,
                                'label'  =>$this->annotationReader->getOneLabel($data, $name),
                            //     'attr' => ['class' => 'col-lg-6']

                            ]);
                             }
                             }
                            else {
                                throw new \RuntimeException('Une erreur se produite lors de la création du formulaire');
                            }
                        }
                }
            }
        } catch (\ReflectionException $e) {
        }
    }
}
