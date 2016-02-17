<?php namespace CAT\Flash;

use Illuminate\Http\Request;
use InvalidArgumentException;

class FlashNotifier
{
    protected $session;
    
    protected $types = [
        'alert',
        'notification',
        'modal',
    ];
    
    protected $callRegex;
    
	public function __construct(Request $request)
    {
        $this->session = $request->session();
    }
    
    public function message($message, $level = 'info', $type = 'alert')
    {
        $this->session->flash('flash.' . $type . 's', array_merge($this->session->get('flash.' . $type . 's', []), [
            'message' => $message,
            'level' => $level,
        ]));
    }
    
    public function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        return redirect($to, $status, $headers, $secure);
    }
    
    public function __call($method, $args)
    {
        if (! isset($args[0])) {
            throw new InvalidArgumentException('Missing $message argument for ' get_class($this) . '::' . $method);
        }
        
        $message = $args[0];
        $type = $method;
        
        if (in_array($method, $this->types)) {
            $level = array_get($args, 1);
        } elseif (preg_match($this->getCallRegex(), $method, $matches)) {
            list($match, $level, $type) = $matches;
        } else {
            $level = null;
        }
        
        $this->message($message, $level, $type);
        
        return $this;
    }
    
    protected function getCallRegex()
    {
        if (! $this->callRegex) {
            $types = implode('|', array_map(function($type) {
                $type = preg_replace_callback('^\w', function($matches) {
                    return '(?:' . strtoupper($matches[0]) . '|_' . $matches[0] . ')';
                }, $type);
            }, $this->types));
            
            $this->callRegex = '/(\w+)(' . $types . ')/';
        }
        
        return $this->callRegex;
    }
} 