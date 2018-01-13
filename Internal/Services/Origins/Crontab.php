<?php namespace ZN\Services;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

use ZN\Services\Exception\InvalidTimeFormatException;
use ZN\DataTypes\Arrays;
use ZN\FileSystem\File;

class Crontab extends RemoteCommon implements CrontabInterface, CrontabIntervalInterface
{
    const config = ['Services:processor'];

    //--------------------------------------------------------------------------------------------------------
    // Crontab Interval
    //--------------------------------------------------------------------------------------------------------
    //
    // comands
    //
    //--------------------------------------------------------------------------------------------------------
    use CrontabIntervalTrait;

    //--------------------------------------------------------------------------------------------------------
    // Type
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $type;

    //--------------------------------------------------------------------------------------------------------
    // Debug
    //--------------------------------------------------------------------------------------------------------
    //
    // @var boolean: false
    //
    //--------------------------------------------------------------------------------------------------------
    protected $debug = false;

    //--------------------------------------------------------------------------------------------------------
    // Driver
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $crontabDir = '';

    //--------------------------------------------------------------------------------------------------------
    // Jobs
    //--------------------------------------------------------------------------------------------------------
    //
    // @var array
    //
    //--------------------------------------------------------------------------------------------------------
    protected $jobs = [];

    //--------------------------------------------------------------------------------------------------------
    // Zerocore
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $zerocore = 'define("CONSOLE_ENABLED", true); require_once "' . REAL_BASE_DIR . 'zeroneed.php"; ';

     //--------------------------------------------------------------------------------------------------------
    // dZerocore
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $dzerocore = NULL;

    //--------------------------------------------------------------------------------------------------------
    // Crontab Commands
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $crontabCommands;

    //--------------------------------------------------------------------------------------------------------
    // Fil Name
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $fileName = 'Crontab' . DS . 'Jobs';

    //--------------------------------------------------------------------------------------------------------
    // User
    //--------------------------------------------------------------------------------------------------------
    //
    // @var string
    //
    //--------------------------------------------------------------------------------------------------------
    protected $user = NULL;

    //--------------------------------------------------------------------------------------------------------
    // Constructor
    //--------------------------------------------------------------------------------------------------------
    //
    // __costruct()
    //
    //--------------------------------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        if( PROJECT_TYPE === 'EIP' )
        {
            $this->crontabCommands = EXTERNAL_DIR . $this->fileName;
            $this->user            = defined('_CURRENT_PROJECT') 
                                   ? _CURRENT_PROJECT
                                   : CURRENT_PROJECT;
            
            $this->_project($this->dzerocore = $this->zerocore);
        }
        else
        {
            $this->crontabCommands = FILES_DIR . $this->fileName;
        }

        $this->path       = SERVICES_PROCESSOR_CONFIG['path'];
        $this->crontabDir = File\Info::originpath(STORAGE_DIR.'Crontab'.DS);
    }

    //--------------------------------------------------------------------------------------------------------
    // Queue -> 5.3.6
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  int      $id
    // @param  callable $callable
    // @oaram  int      $decrement = 1
    // @return void
    //
    //--------------------------------------------------------------------------------------------------------
    public function queue(Int $id, Callable $callable, Int $decrement = 1)
    {
        $queueFile = $this->crontabCommands . 'Queue.json';
        
        $fileLimitValue = 0;

        $key = 'ID' . $id;

        if( ! is_file($queueFile) )
        {
            file_put_contents($queueFile, json_encode([$key => $fileLimitValue]) . EOL);
        }
        
        $fileData = json_decode(file_get_contents($queueFile), true);

        $fileLimitValue = (int) ($fileData[$key] ?? NULL);

        if( $callable($fileLimitValue, $decrement) === false )
        {
            $this->remove((int) ltrim($key, 'ID'));

            if( isset($fileData[$key]) )
            {
                unset($fileData[$key]);
            }
        }
        else
        {
            $fileData[$key] = $fileLimitValue += $decrement;
        }
           
        file_put_contents($queueFile, json_encode($fileData) . EOL);
    }

    //--------------------------------------------------------------------------------------------------------
    // Limit -> 5.3.6
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  int $id
    // @oaram  int $limit = 1
    // @return void
    //
    //--------------------------------------------------------------------------------------------------------
    public function limit(Int $id, Int $limit = 1)
    {
        $limitFile = $this->crontabCommands . 'Limit.json';
        
        $fileLimitValue = $default = 1;

        $key = 'ID' . $id;

        if( ! is_file($limitFile) )
        {
            file_put_contents($limitFile, json_encode([$key => $default]) . EOL);
        }
        
        $fileData = json_decode(file_get_contents($limitFile), true);

        $fileLimitValue = (int) ($fileData[$key] ?? NULL);

        if( $fileLimitValue === $limit )
        {
            $this->remove((int) ltrim($key, 'ID'));

            if( isset($fileData[$key]) )
            {
                unset($fileData[$key]);
            }
        }
        else
        {
            $fileData[$key] = $fileLimitValue++;   
        }

        file_put_contents($limitFile, json_encode($fileData) . EOL);
    }

    //--------------------------------------------------------------------------------------------------------
    // Project -> 5.3.56
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $name
    // @return object
    //
    //--------------------------------------------------------------------------------------------------------
    public function project(String $name)
    {
        $this->user = $name;

        $this->crontabDir = str_replace(_CURRENT_PROJECT, $this->user, $this->crontabDir);

        $this->_project($this->dzerocore);

        return $this;
    }

    //--------------------------------------------------------------------------------------------------------
    // Driver
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $driver: empty
    // @return object
    //
    //--------------------------------------------------------------------------------------------------------
    public function driver(String $driver) : Crontab
    {
        \Processor::driver($driver);
        
        return $this;
    }

    //--------------------------------------------------------------------------------------------------------
    // List Array
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return array
    //
    //--------------------------------------------------------------------------------------------------------
    public function listArray() : Array
    {
        if( ! is_file($this->crontabCommands) )
        {
            return [];
        }

        return Arrays\RemoveElement::element(explode(EOL, file_get_contents($this->crontabCommands)), '');
    }

    //--------------------------------------------------------------------------------------------------------
    // List
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function list() : String
    {
        $list = '';

        if( is_file($this->crontabCommands) )
        {
            $jobs  = $this->listArray();
            $list  = '<pre>';
            $list .= '[ID] CRON JOB' . \Html::br(2);

            foreach( $jobs as $key => $job )
            {
                $list .= '[' . $key . ']: '. $job . \Html::br();
            }

            $list .= '</pre>';
        }

        return $list;
    }

    //--------------------------------------------------------------------------------------------------------
    // Last Job
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function lastJob()
    {
        return \Processor::exec('crontab -l');
    }

    //--------------------------------------------------------------------------------------------------------
    // Remove
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $key
    // @return void
    //
    //--------------------------------------------------------------------------------------------------------
    public function remove($key = NULL)
    {
        \Processor::exec('crontab -r');

        if( $key === NULL )
        {
            unlink($this->crontabCommands);
        }
        else
        {
            $jobs = $this->listArray();

            unset($jobs[$key]);

            file_put_contents($this->crontabCommands, implode(EOL, $jobs) . EOL);

            \Processor::exec('crontab ' . $this->crontabCommands);
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Debug
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  bool   $status: true
    // @return object
    //
    //--------------------------------------------------------------------------------------------------------
    public function debug(Bool $status = true) : Crontab
    {
        $this->debug = $status;
        return $this;
    }

    //--------------------------------------------------------------------------------------------------------
    // Controller
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $file: empty
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function controller(String $file)
    {
        $path = $this->_convertFileName($file);
        $code = prefix(suffix($this->_controller($file), ';\''), ' -r \'' . $this->zerocore);

        $this->run($code);
    }

    //--------------------------------------------------------------------------------------------------------
    // Wget
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $file: empty
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function wget(String $url)
    {
        $this->path('wget');
        $this->run($url);
    }

    //--------------------------------------------------------------------------------------------------------
    // Controller
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $file: empty
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function command(String $file, $type = 'Project')
    {
        $path     = $this->_convertFileName($file);
        $pathEx   = explode('-', $path);
        $command  = $pathEx[0];
        $method   = $pathEx[1] ?? 'main';

        $code = ' -r \'' . $this->zerocore . '(new \\'.$type.'\Commands\\'.$command.')->'.$method.'();\'';

        $this->run($code);
    }

    //--------------------------------------------------------------------------------------------------------
    // Run
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $cmd: empty
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    public function run(String $cmd = NULL)
    {
        $execFile = $this->crontabCommands;

        if( ! is_file($execFile) )
        {
            File\Forge::create($execFile);
            \Processor::exec('chmod 0777 ' . $execFile);
        }

        $content = file_get_contents($execFile);

        if( ! stristr($content, $cmd))
        {
            $content = $content . $this->_command() . $cmd . EOL;
            file_put_contents($execFile, $content);
        }

        return \Processor::exec('crontab ' . $execFile);
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Project -> 5.3.55
    //--------------------------------------------------------------------------------------------------------
    //
    // @param void
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _project($value)
    {
        $this->zerocore = 'define("CONSOLE_PROJECT_NAME", "'.$this->user.'"); ' . $value;
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Convert File Name
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  string $file
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _convertFileName($file)
    {
        return str_replace(['/', ':'], '-', $file);
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Date Time
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _datetime()
    {
        if( $this->interval !== '* * * * *' )
        {
            $interval = $this->interval.' ';
        }
        else
        {
            $interval = ( $this->minute    ?? '*' ) . ' '.
                        ( $this->hour      ?? '*' ) . ' '.
                        ( $this->dayNumber ?? '*' ) . ' '.
                        ( $this->month     ?? '*' ) . ' '.
                        ( $this->day       ?? '*' ) . ' ';
        }

        $this->_intervalDefaultVariables();

        return $interval;
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Date Time
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return string
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _command()
    {
        $datetimeFormat = $this->_datetime();
        $type           = $this->type;
        $path           = $this->path;
        $command        = $this->command;
        $debug          = $this->debug;

        $match = '(\*|[0-9]{1,2}|\*\/[0-9]{1,2}|[0-9]{1,2}\s*\-\s*[0-9]{1,2}|(([0-9]{1,2})*\s*\,\s*[0-9]{1,2})+)\s+';

        if( ! preg_match('/^'.$match.$match.$match.$match.$match.'$/', $datetimeFormat) )
        {
            throw new InvalidTimeFormatException('Services', 'crontab:timeFormatError');
        }
        else
        {
            return $datetimeFormat.
                   ( ! empty($path)    ? $path    . ' ' : '' ).
                   ( ! empty($command) ? $command . ' ' : '' ).
                   ( ! empty($type)    ? $type    . ' ' : '' ).
                   ( $debug === true   ? '>> '    . $this->crontabDir . 'debug.log 2>&1' : '' );
        }
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Date Time
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return void
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _defaultVariables()
    {
        $this->type     = NULL;
        $this->path     = NULL;
        $this->command  = NULL;
        $this->debug    = false;
    }

    //--------------------------------------------------------------------------------------------------------
    // Protected Date Time
    //--------------------------------------------------------------------------------------------------------
    //
    // @param  void
    // @return void
    //
    //--------------------------------------------------------------------------------------------------------
    protected function _intervalDefaultVariables()
    {
        $this->interval  = '* * * * *';
        $this->minute    = '*';
        $this->hour      = '*';
        $this->dayNumber = '*';
        $this->month     = '*';
        $this->day       = '*';
    }
}
