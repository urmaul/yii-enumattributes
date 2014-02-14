<?php

/**
 * @property-read CActiveRecord $owner
 * 
 * @property-read array $values
 * @property-read array $valueLabels
 * @property-read array $visibleLabels
 * @property-read array $rule enum "in" validation rule.
 */
class EnumAttributesBehavior extends CActiveRecordBehavior
{
    /**
     * Enum attribute name
     * @var string
     */
    public $attribute;
    
    /**
     * Predefined labels
     * @var string[]
     */
    public $labels;
    
    /**
     * Visible values array.
     * @var string[]
     */
    public $visible;
    
    
    public function getValues()
    {
        $dbType = $this->owner->tableSchema->columns[$this->attribute]->dbType;
        $off = strpos($dbType, '(');
        $enum = substr($dbType, $off+1, -1);
        
        $values = str_replace("'", null, explode(',', $enum));
        
        return $values;
    }
    
    public function getValueLabels()
    {
        $values = $this->values;
        $labels = array();
        
        foreach ($values as $value) {
            if ( isset($this->labels[$value]) )
                $labels[$value] = $this->labels[$value];
            else
                $labels[$value] = $this->owner->generateAttributeLabel($value);
        }
        
        return $labels;
    }
    
    public function getVisibleLabels()
    {
        return $this->getLabelsFor($this->visible);
    }

    /**
     * Returns enum "in" validation rule.
     * @return array
     */
    public function getRule()
    {
        return array($this->attribute, 'in', 'range' => $this->values);
    }
    
    protected function getLabelsFor($values)
    {
        $labels = array();
        foreach ($values as $value) {
            if (isset($this->labels[$value]))
                $labels[$value] = $this->labels[$value];
            else
                $labels[$value] = $this->owner->generateAttributeLabel($value);
        }
        
        return $labels;
    }
}
