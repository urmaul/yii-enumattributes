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
    public $attribute;
    public $labels;
    /**
     * Visible values array.
     * @var string[]
     */
    public $visible;
    
    private $_cacheKey;
    
    protected static $_cache = array();
    
    
    public function attach($owner)
    {
        parent::attach($owner);
        
        $this->_cacheKey = get_class($owner) . '.' . $this->attribute;
    }
    
    public function getValues()
    {
        $cName = 'values';
        
        if ( $this->_hasCache($cName) )
            return $this->_getCache($cName, false);
        
        $dbType = $this->owner->tableSchema->columns[$this->attribute]->dbType;
        $off = strpos($dbType, '(');
        $enum = substr($dbType, $off+1, -1);
        
        $values = str_replace("'", null, explode(',', $enum));
        
        $this->_setCache($cName, $values);
        
        return $values;
    }
    
    public function getValueLabels()
    {
        $cName = 'valueLabels';
        
        if ( $this->_hasCache($cName) )
            return $this->_getCache($cName, false);
        
        $values = $this->values;
        $labels = array();
        
        foreach ($values as $value) {
            if ( isset($this->labels[$value]) )
                $labels[$value] = $this->labels[$value];
            else
                $labels[$value] = $this->owner->generateAttributeLabel($value);
        }
        
        $this->_setCache($cName, $values);
        
        return $labels;
    }
    
    public function getVisibleLabels()
    {
        $cName = 'valueLabels';
        
        if ( $this->_hasCache($cName) )
            return $this->_getCache($cName, false);
        
        $labels = $this->getLabelsFor($this->visible);
        
        $this->_setCache($cName, $labels);
        
        return $labels;
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
            if ( isset($this->labels[$value]) )
                $labels[$value] = $this->labels[$value];
            else
                $labels[$value] = $this->owner->generateAttributeLabel($value);
        }
        
        return $labels;
    }
    
    
    /* Cache functions */
    
    protected function _hasCache($name)
    {
        return isset(self::$_cache[$this->_cacheKey], self::$_cache[$this->_cacheKey][$name]);
    }
    
    protected function _getCache($name, $check = true)
    {
        if ( !$check || $this->_hasCache($name) )
            return self::$_cache[$this->_cacheKey][$name];
        else
            return null;
    }
    
    protected function _setCache($name, $value)
    {
        self::$_cache[$this->_cacheKey][$name] = $value;
    }
}
