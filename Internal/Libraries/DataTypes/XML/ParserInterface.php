<?php namespace ZN\DataTypes\XML;

interface ParserInterface
{
    //--------------------------------------------------------------------------------------------------------
    // Parse
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $xml
    // @param string $result
    //
    //--------------------------------------------------------------------------------------------------------
    public function do(String $xml, String $result = 'object');

    //--------------------------------------------------------------------------------------------------------
    // Parse Array
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $data
    // @return array
    //
    //--------------------------------------------------------------------------------------------------------
    public function array(String $data) : Array;

    //--------------------------------------------------------------------------------------------------------
    // Parse Json
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $data
    // @return array
    //
    //--------------------------------------------------------------------------------------------------------
    public function json(String $data) : String;

    //--------------------------------------------------------------------------------------------------------
    // Parse Object
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string   $data
    // @return object
    //
    //--------------------------------------------------------------------------------------------------------
    public function object(String $data) : \stdClass;
}
