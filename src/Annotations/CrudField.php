<?php


namespace App\Annotations;
/**
 * @Annotation
 * @Target("PROPERTY")
 */

class CrudField
{
    private $type;
    private $label;
    private $entity;
    /**
     * CrudField constructor
     */
    public function __construct(array $options)

    {
        
        if(empty($options['type'])){
            throw new \InvalidArgumentException("L'annotation CrudField doit avoir un attribut type");
        }
        if(empty($options['label'])){
            throw new \InvalidArgumentException("L'annotation CrudField doit avoir un attribut label");
        }
        
        if(empty($options['entity'])){
            $this->entity=null;
        }else{
            $this->entity=$options['entity'];
        }
        
        $this->type=$options['type'];
        $this->label=$options['label'];
        
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

}