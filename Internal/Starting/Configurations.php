<?php
//--------------------------------------------------------------------------------------------------
// Starting
//--------------------------------------------------------------------------------------------------
//
// Author     : Ozan UYKUN <ozanbote@windowslive.com> | <ozanbote@gmail.com>
// Site       : www.znframework.com
// License    : The MIT License
// Copyright  : Copyright (c) 2012-2016, ZN Framework
//
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Ob Start
//--------------------------------------------------------------------------------------------------
//
// Tampon başlatılıyor.
//
//--------------------------------------------------------------------------------------------------
if( Config::get('IndividualStructures', 'cache')['obGzhandler'] && substr_count(server('acceptEncoding'), 'gzip') )
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Headers
//--------------------------------------------------------------------------------------------------
//
// Başlık bilgileri düzenleniyor.
//
//--------------------------------------------------------------------------------------------------
headers(Config::get('General', 'headers'));
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Set Error Handler
//--------------------------------------------------------------------------------------------------
//
// Yakanalan hata set ediliyor.
//
//--------------------------------------------------------------------------------------------------
if( PROJECT_MODE !== 'publication' )
{
    set_error_handler('Exceptions::table');
}
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// INI Ayarlarını Yapılandırma İşlemi
//--------------------------------------------------------------------------------------------------
$iniSet = Config::get('Htaccess', 'ini')['settings'];

if( ! empty($iniSet) )
{
    Config::iniSet($iniSet);
}
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Htaccess Dosyası Oluşturma İşlemi
//--------------------------------------------------------------------------------------------------
if( Config::get('Htaccess','createFile') === true )
{
    internalCreateHtaccessFile();
}
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Robots Dosyası Oluşturma İşlemi
//--------------------------------------------------------------------------------------------------
if( Config::get('Robots','createFile') === true )
{
    internalCreateRobotsFile();
}
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// Composer Autoloader
//--------------------------------------------------------------------------------------------------
$composer = Config::get('Autoloader', 'composer');

if( $composer === true )
{
    $path = 'vendor/autoload.php';

    if( file_exists($path) )
    {
        require_once($path);
    }
    else
    {
        report('Error', lang('Error', 'fileNotFound', $path) ,'AutoloadComposer');

        die(Errors::message('Error', 'fileNotFound', $path));
    }
}
elseif( is_file($composer) )
{
    require_once($composer);
}
elseif( ! empty($composer) )
{
    report('Error', lang('Error', 'fileNotFound', $composer) ,'AutoloadComposer');

    die(Errors::message('Error', 'fileNotFound', $composer));
}
//--------------------------------------------------------------------------------------------------------
