<?php
/**
 * @category Oxy
 * @package  Oxy\Domain
 * @author   Tomas Bartkus <to.bartkus@gmail.com>
 */

namespace Oxy\Domain\ValueObject;

/**
 * @package Oxy\Domain\ValueObject
 * @author  Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class ArrayableAbstract
{
    /**
     * Convert object to array
     * 
     * @return array
     */
    public function toArray()
    {
        $properties = get_class_vars(get_class($this));
        $vars = array();
        foreach ($properties as $name => $defaultVal) {
            $vars[str_replace('_', '', $name)] = $this->$name; 
        }  

        return $vars;
    }
}