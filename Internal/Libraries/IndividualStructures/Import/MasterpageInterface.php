<?php namespace ZN\IndividualStructures\Import;

interface MasterpageInterface
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
    // headData()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string $headData
    //
    //--------------------------------------------------------------------------------------------------------
    public function headData(Array $headData) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // body()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string $body
    //
    //--------------------------------------------------------------------------------------------------------
    public function body(String $body) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // head()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var mixed $head
    //
    //--------------------------------------------------------------------------------------------------------
    public function head($head) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // title()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string $title
    //
    //--------------------------------------------------------------------------------------------------------
    public function title(String $title) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // meta()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var array $meta
    //
    //--------------------------------------------------------------------------------------------------------
    public function meta(Array $meta) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // attributes()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var array $attributes
    //
    //--------------------------------------------------------------------------------------------------------
    public function attributes(Array $attributes) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // content()
    //--------------------------------------------------------------------------------------------------------
    //
    // @var array $content
    //
    //--------------------------------------------------------------------------------------------------------
    public function content(Array $content) : Masterpage;

    //--------------------------------------------------------------------------------------------------------
    // masterpage()
    //--------------------------------------------------------------------------------------------------------
    //
    // @param array $randomDataVariable
    // @param array $head
    //
    //--------------------------------------------------------------------------------------------------------
    public function use(Array $randomDataVariable = NULL, Array $head = NULL);
}
