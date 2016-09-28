<?php namespace ZN\ViewObjects\Bootstrap;

use Import, CallController;

class InternalScript extends CallController implements TextCommonInterface
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
    // Ready
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var bool
    //
    //--------------------------------------------------------------------------------------------------------
    protected $ready = true;
    
    //--------------------------------------------------------------------------------------------------------
    // Type
    //--------------------------------------------------------------------------------------------------------
    // 
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $type  = 'text/javascript';
    
    //--------------------------------------------------------------------------------------------------------
    // Type
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param string $type()
    //
    //--------------------------------------------------------------------------------------------------------
    public function type(String $type)
    {
        $this->type = $type;
        
        return $this;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Library
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param variadic $libraries
    //
    //--------------------------------------------------------------------------------------------------------
    public function library(...$libraries)
    {
        Import::script(...$libraries);
        
        return $this;
    }
    
    //--------------------------------------------------------------------------------------------------------
    // Open
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param bool $ready
    // @param bool $jqueryCdn
    // @param bool $jqueryUiCdn
    //
    //--------------------------------------------------------------------------------------------------------
    public function open(Bool $ready = true, Bool $jqueryCdn = false, Bool $jqueryUiCdn = false) : String
    {       
        $this->ready = $ready;
        
        $eol     = EOL;
        $script  = "";
        
        if( $jqueryCdn === true ) 
        {
            $script .= Import::script('jquery', true);
        }
        
        if( $jqueryUiCdn === true ) 
        {
            $script .= Import::script('jqueryUi', true);
        }
        
        $script .= "<script type=\"$this->type\">".$eol;
        
        if( $this->ready === true )
        {
            $script .= "$(document).ready(function()".$eol."{".$eol;
        }
        
        return $script;
    }

    //--------------------------------------------------------------------------------------------------------
    // Close
    //--------------------------------------------------------------------------------------------------------
    // 
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    public function close() : String
    {   
        $script = "";
        $eol    = EOL;
        
        if( $this->ready === true )
        {
            $script .= $eol.'});';
        }
        
        $script .=  $eol.'</script>'.$eol;
        
        return $script;
    }   
}