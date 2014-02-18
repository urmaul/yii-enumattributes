<?php

/**
 * @property-read CActiveRecord $owner
 * 
 * @property-read array $values
 * @property-read array $valueLabels
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
     * Values groups.
     * @var string[]
     */
    public $groups;
    
    
    public function canGetProperty($name)
    {
        return
            $this->groupName($name) ||
            parent::canGetProperty($name);
    }
    
    public function __get($name)
    {
        if ($groupName = $this->groupName($name))
            return $this->getGroupLabels($groupName);
        else
            return parent::__get($name);
    }
    
    public function getValues()
    {
        $dbType = $this->getOwner()->tableSchema->columns[$this->attribute]->dbType;
        $off = strpos($dbType, '(');
        $enum = substr($dbType, $off+1, -1);

        $values = str_replace("'", null, explode(',', $enum));

        return $values;
    }
    
    public function getValueLabels($values = null)
    {
        if ($values === null)
            $values = $this->values;
        
        return $this->getLabelsFor($values);
    }
    
    public function getGroupLabels($group)
    {
        $values = $this->groups[$group];
        return $this->getValueLabels($values);
    }

    /**
     * Returns enum "in" validation rule.
     * @return array
     */
    public function getRule($group = null)
    {
        if ($group === null)
            $values = $this->getValues();
        else
            $values = $this->groups[$group];

        return array($this->attribute, 'in', 'range' => $values);
    }
    
    protected function getLabelsFor($values)
    {
        $owner = $this->getOwner();
        $labels = array();
        foreach ($values as $value) {
            if (isset($this->labels[$value]))
                $labels[$value] = $this->labels[$value];
            else
                $labels[$value] = $owner->generateAttributeLabel($value);
        }
        
        return $labels;
    }

    private function groupName($property)
    {
        if (preg_match('~^(.*)Labels$~', $property, $match) && isset($this->groups[$match[1]]))
            return $match[1];
        else
            return false;
    }
}
