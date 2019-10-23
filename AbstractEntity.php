<?php
if (!defined('TAB')) {
    define('TAB', "\t");
}

/**
 * Abstract class that provides resource to return class attributes like xml, 
 * json and string
 *
 * @author Tayron Miranda <contato@tayron.com.br>
 */
class AbstractEntity
{
    /**
     * Store attribute name with HTML content
     * 
     * @var array
     */
    private $attributeWithHtml = array();

    /**
     * AbstractEntity::setAttributeWithHtml
     * 
     * Store attribute name with HTML content
     * 
     * @return void
     */
    public function setAttributeWithHtml($attribute)
    {
        array_push($this->attributeWithHtml, $attribute);
    }

    /**
     * AbstractEntity::toXml
     * 
     * Return the class attributes like XML
     * 
     * @return string XML with class attributes
     */
    public function toXml($nameItem = null)
    {
        $attributes = $this->getDaughterClassProperties();
        $nameList = explode('\\', get_class($this));
        $className = is_null($nameItem) ? end($nameList) : $nameItem;

        $tagXML = '<%s>%s%s</%s>';
        $tagItem = '<%s>%s</%s>';
        $tagItemCdata = '<%s><![CDATA[%s]]></%s>';

        $itemXml = '';
        foreach ($attributes as $item) {
            $attribute = $item->name;
            $methodGet = 'get' . ucfirst($attribute);

            if (!method_exists($this, $methodGet)) {
                throw new \BadMethodCallException('Please, implement the get method: ' . $methodGet);
            }

            $value = $this->$methodGet();
            $tagItemToUse = (in_array($attribute, $this->attributeWithHtml)) ? $tagItemCdata : $tagItem;
            $itemXml .= TAB . sprintf($tagItemToUse, $attribute, $value, $attribute) . PHP_EOL;
        }
        return sprintf($tagXML, $className, PHP_EOL, $itemXml, $className);
    }

    /**
     * AbstractEntity::toJson
     * 
     * Return the class attributes like Json
     * 
     * @return string Json with class attributes
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * AbstractEntity::toArray
     * 
     * Return the list with object data
     * 
     * @return array array with class attributes
     */
    public function toArray()
    {
        $attributes = $this->getDaughterClassProperties();
        $listAttributes = [];
        foreach ($attributes as $propriedade) {
            $attribute = $propriedade->name;
            $methodGet = 'get' . ucfirst($attribute);
            $value = $this->$methodGet();

            $listAttributes[$attribute] = $this->getPropertyValue($value);
        }
        return $listAttributes;
    }

    /**
     * AbstractEntity::getPropertyValue
     * 
     * Return the value from value from property
     * 
     * @return mixed Value from property
     */
    private function getPropertyValue($property)
    {
        if (is_array($property)) {
            $arrayValue = array();
            foreach ($property as $key => $value) {
                $arrayValue[$key] = is_object($value)
                    ? $value->toArray() : $value;
            }
            return $arrayValue;
        }

        if (!is_object($property)) {
            return $property;
        }

        if ($property instanceof $this) {
            return $property->toArray();
        }

        return (array) $property;
    }

    /**
     * AbstractEntity::toArrayObject
     * 
     * Return the class attributes like array
     * 
     * @return array array with class attributes
     */
    public function toArrayObject()
    {
        $attributes = $this->getDaughterClassProperties();
        $listAttributes = new \ArrayObject();
        foreach ($attributes as $propriedade) {
            $attribute = $propriedade->name;
            $methodGet = 'get' . ucfirst($attribute);
            $value = $this->$methodGet();
            $listAttributes[$attribute] = $value;
        }
        return $listAttributes;
    }

    /**
     * AbstractEntity::__toString
     * 
     * Return the class attributes like string
     * 
     * @return string string with class attributes
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * AbstractEntity::toString
     * 
     * Return the class attributes like string
     * 
     * @return string string with class attributes
     */
    public function toString()
    {
        $attributes = $this->getDaughterClassProperties();
        $text = null;

        foreach ($attributes as $item) {
            $attribute = $item->name;
            $methodGet = 'get' . ucfirst($attribute);
            $value = $this->$methodGet();
            $text .= "$attribute: $value, ";
        }
        return $text;
    }

    /**
     * 
     * AbstractEntity::toSerialize
     * 
     * Return the class serialized
     * 
     * @return string the class serialized
     */
    public function toSerialize()
    {
        return serialize($this);
    }

    /**
     * AbstractEntity::getDaughterClassProperties
     * 
     * Return the list of the doughter class attributes
     * 
     * @return array
     */
    private function getDaughterClassProperties()
    {
        $reflectionClass = new \ReflectionClass($this);
        return $reflectionClass->getProperties();
    }
}
