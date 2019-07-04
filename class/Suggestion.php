<?php
class Suggestion
{
    private $word;
    private $clicks;


    public function __construct($word, $clicks)
    {
        $this->word = $word;
        $this->clicks = $clicks;
    }


    public function __set($property, $value) {
        if(property_exists($this, $property))
            $this->$property = $value;
    }

    public function __get($property) {
        return $this->$property;
    }

}
