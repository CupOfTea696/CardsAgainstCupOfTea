<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Conditional validation rules based on a Closure.
     *
     * @var array
     */
    protected $sometimes = [];
    
    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param  string|array  $attribute
     * @param  string|array  $rules
     * @param  callable  $callback
     * @return object
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        array_push($this->sometimes, compact('attribute', 'rules', 'callback'));
        return $this;
    }
    
    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->makeValidator($request->all(), $rules, $messages, $customAttributes);
        
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }
    
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    protected function makeValidator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);
        
        foreach ($this->sometimes as $sometimes) {
            $validator->sometimes($sometimes['attribute'], $sometimes['rules'], $sometimes['callback']);
        }
        
        $this->sometimes = [];
        
        return $validator;
    }
}
