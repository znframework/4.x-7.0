<?php namespace ZN\IndividualStructures\Compress\Drivers;

class ZlibDriver extends Abstracts\CompressDriverMappingAbstract
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
    // Construct
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function __construct()
    {
        \Support::func('zlib_encode', 'ZLIB');  
    }

    //--------------------------------------------------------------------------------------------------------
    // Extract
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function extract($source, $target, $password)
    {
        \Support::func('zlib_extract', 'ZLIB Driver Extract');   
    }

    //--------------------------------------------------------------------------------------------------------
    // Write
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string $file
    // @param string $data
    //
    //--------------------------------------------------------------------------------------------------------
    public function write($file, $data)
    {
        $data = $this->do($data);

        return \File::write($file, $data);
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Read
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string  $file
    //
    //--------------------------------------------------------------------------------------------------------
    public function read($file)
    {
        $content = \File::read($file);

        return $this->undo($content);
    }

    //--------------------------------------------------------------------------------------------------------
    // Do
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string  $data
    //
    //--------------------------------------------------------------------------------------------------------
    public function do($data)
    {
        return zlib_encode($data, ZLIB_ENCODING_GZIP);
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Undo
    //--------------------------------------------------------------------------------------------------------
    //
    // @param string  $data
    //
    //--------------------------------------------------------------------------------------------------------
    public function undo($data)
    {
        return zlib_decode($data);
    }
}