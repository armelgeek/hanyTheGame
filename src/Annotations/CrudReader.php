<?php


namespace App\Annotations;

use Doctrine\Common\Annotations\Reader;

class CrudReader
{
    private $annotationReader;
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }
    public function isCruddable($entity):bool {
        $reflexion = new \ReflectionClass(get_class($entity));
        return $this->annotationReader->getClassAnnotation($reflexion,Crudding::class)!==null;
    }
    public function getCrudFields($entity):array
    {
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
            $annotation = $this->annotationReader->getPropertyAnnotation($property,CrudField::class);
            if($annotation!==null){
                $properties[$property->getName()]=$annotation;
            }
        }
        return $properties;
    }
    public function getTableFields($entity):array
    {
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
            $annotation = $this->annotationReader->getPropertyAnnotation($property,CrudListField::class);
            if($annotation!==null){
                $properties[]=$property->getName();
            }
        }
        return $properties;
    }
    public function getTableContentFields($entity):array
    {
        
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
            $annotation = $this->annotationReader->getPropertyAnnotation($property,CrudListField::class);
           
            if($annotation!==null){
                $properties[$property->getName()]=$annotation->getField();
            }
        }
        return $properties;
    }
    
    public function getTableLabelFields($entity):array
    {
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
            $annotation = $this->annotationReader->getPropertyAnnotation($property,CrudListField::class);
            if($annotation!==null){
             //   dd($annotation);
                $properties[$property->getName()]=$annotation->getLabel();
            }
        }
        return $properties;
    }
    public function getCrudFieldSortBy($entity):array
    {
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
            $annotation = $this->annotationReader->getPropertyAnnotation($property,CrudFieldSortBy::class);
            if($annotation!==null){
                $properties[]='row.'.$property->getName();
            }
        }
        return $properties;
    }

    public function isCrudField($entity,$proper):bool {

        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return false;
        }
        foreach ($reflexion->getProperties() as $property) {
            if($property->getName()==$proper){
            $annotation = $this->annotationReader->getPropertyAnnotation($property, CrudField::class);
            if ($annotation !== null) {
                return true;
                break;
            }
            }
        }
        return false;
    }
    public function getOneField($entity,$proper) {

        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return false;
        }
        foreach ($reflexion->getProperties() as $property) {
            if($property->getName()==$proper){
                $annotation = $this->annotationReader->getPropertyAnnotation($property, CrudField::class);
                return $annotation->getType();
            }
        }
        return null;
    }
    public function getOneLabel($entity,$proper) {

        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return false;
        }
        foreach ($reflexion->getProperties() as $property) {
            if($property->getName()==$proper){
                $annotation = $this->annotationReader->getPropertyAnnotation($property, CrudField::class);
                return $annotation->getLabel();
            }
        }
        return null;
    }
    
    public function getOneEntite($entity,$proper) {

        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return false;
        }
        foreach ($reflexion->getProperties() as $property) {
            if($property->getName()==$proper){
                $annotation = $this->annotationReader->getPropertyAnnotation($property, CrudField::class);
                return $annotation->getEntity();
            }
        }
        return null;
    }
    public function getOneForm($entity,$proper) {

        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return false;
        }
        foreach ($reflexion->getProperties() as $property) {
            if($property->getName()==$proper){
                $annotation = $this->annotationReader->getPropertyAnnotation($property, CrudField::class);
                return $annotation->getForm();
            }
        }
        return null;
    }
    public function getCrudFieldShow($entity):array
    {
        $reflexion = new \ReflectionClass(get_class($entity));
        if (!$this->isCruddable($entity)){
            return [];
        }
        $properties=[];
        foreach ($reflexion->getProperties() as $property){
         if ($property->getName()!="imageSize" && $property->getName()!="id" && $property->getName()!="logo"  ) {
            $properties[]=$property->getName();
         }
               
         
        }
        return $properties;
    }
}