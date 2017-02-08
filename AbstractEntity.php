<?php
namespace Tayron;

/**
 * Classe abstrata que disponibiliza recurso para retornar os atributos da classe
 * como xml, json e string
 *
 * @author Tayron Miranda <dev@tayron.com.br>
 */
class AbstractEntity 
{
    /**
     * AbstractEntity::toXml
     * 
     * Método que retorna os atributos da classe filha na estrutura de xml
     * 
     * @return xml XML com os atributos da classe pai
     */
    public function toXml()
    {
        $attributes = $this->getDaughterClassProperties();        
        $className = end(explode('\\', get_class($this)));        
        $xml = "<" . $className . "> \n";
        
        foreach($attributes as $item){
            $attribute = $item->name;
            $methodGet = 'get' . ucfirst($attribute);            
            $value = $this->$methodGet();
            
            $xml .= "\t <$attribute>$value</$attribute> \n";
        }

        $xml .= "</" . $className . "> \n";

        return $xml;
    }
    
    /**
     * AbstractEntity::toJson
     * 
     * Método que retorna os atributos da classe filha na estrutura de json
     * 
     * @return json Json com os atributos da classe pai
     */
    public function toJson()
    {
        return json_encode((array)$this);
    }
    
    /**
     * AbstractEntity::toArray
     * 
     * Método que retorna os atributos da classe filha na estrutura de array
     * 
     * @return ArrayObject ArrayObject com os atributos da classe pai
     */
    public function toArray()
    {
        $attributes = $this->getDaughterClassProperties();        
        $listAttributes = new \ArrayObject();        
        foreach($attributes as $propriedade){
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
     * Método que retorna os atributos da classe filha como string
     * 
     * @return string String com os atributos da classe pai
     */    
    public function __toString() 
    {
        return $this->toString();
    }    
    
    /**
     * AbstractEntity::__toString
     * 
     * Método que retorna os atributos da classe filha como string
     * 
     * @return string String com os atributos da classe pai
     */
    public function toString()
    {
        $attributes = $this->getDaughterClassProperties();        
        $text = null;
        
        foreach($attributes as $item){
            $attribute = $item->name;
            $methodGet = 'get' . ucfirst($attribute);            
            $value = $this->$methodGet();
            $text .= "$attribute: $value, ";
        }

        return $text;        
    }
    
    /**
     * AbstractEntity::getDaughterClassProperties
     * 
     * Método que retorna lista com as propriedades da classe filha
     * 
     * @return array Lista com as propriedades da classe
     */
    private function getDaughterClassProperties()
    {
        $reflectionClass = new \ReflectionClass($this);
        return $reflectionClass->getProperties();        
    }
}