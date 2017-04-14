<?php namespace ZN\ViewObjects\View\BS;

interface BadgeInterface
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
    // Badge Link
    //--------------------------------------------------------------------------------------------------------
    //
    // @paran int    $badge = 5
    //
    //--------------------------------------------------------------------------------------------------------
    public function badge(Int $badge = 5) : String;

    //--------------------------------------------------------------------------------------------------------
    // Badge Link
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $url   = NULL
    // @param string $value = NULL
    // @paran int    $badge = 5
    //
    //--------------------------------------------------------------------------------------------------------
    public function badgeLink(String $url = NULL, String $value = NULL, Int $badge = 5) : String;

    //--------------------------------------------------------------------------------------------------------
    // Badge Link
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $name  = NULL
    // @param string $value = NULL
    // @paran int    $badge = 5
    //
    //--------------------------------------------------------------------------------------------------------
    public function badgeButton(String $name = NULL, String $value = NULL, Int $badge = 5) : String;
}
