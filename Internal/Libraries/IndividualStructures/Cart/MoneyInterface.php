<?php namespace ZN\IndividualStructures\Cart;

interface MoneyInterface
{
    //--------------------------------------------------------------------------------------------------------
    //
    // Author     : Ozan UYKUN <ozanbote@gmail.com>
    // Site       : www.znframework.com
    // License    : The MIT License
    // Copyright  : (c) 2012-2016, znframework.com
    //
    //--------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------
    // Money Format
    //--------------------------------------------------------------------------------------------------------
    //
    // @param int    $money
    // @param string $type
    //
    //--------------------------------------------------------------------------------------------------------
    public function format(Int $money, String $type = NULL) : String;
}
