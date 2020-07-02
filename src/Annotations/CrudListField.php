<?php


namespace App\Annotations;
/**
 * @Annotation
 * @Target("PROPERTY")
 */

class CrudListField
{
    private $field;
    private $label;
    public function __construct(array $options)
    {
        if(empty($options['field'])){
            throw new \InvalidArgumentException("L'annotation CrudListField doit avoir un attribut field");
        }
        
        if(empty($options['label'])){
            throw new \InvalidArgumentException("L'annotation CrudListField doit avoir un attribut label");
        }
        $this->label=$options['label'];
        $this->field=$options['field'];
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
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

}