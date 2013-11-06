<?php

namespace MxcGenerics\ServiceManager\Plugin;

use Zend\Stdlib\SplStack;

abstract class AbstractGenerator extends AbstractPlugin {
    
    protected $optionStack;
    
	public function generate($params, $options = null) {
        $this->getOptionStack()->push($this->options);
	    $this->options = $this->getActualOptions($params, $options);
	     
	    $result = $this->doGenerate($this->options);
	    $reset = $this->options->getResetOptionsAfterRun();
	    $this->options = $this->optionStack->pop();
	    if ($reset) $this->resetOptions();
	    return $result;	    
	}
	
	public function doGenerate($options) {
	    //abstract
	}
	
    protected function getOptionStack() {
        if (!$this->optionStack) {
            $this->optionStack = new SplStack();
        }
        return $this->optionStack;
    }
    
    public function resetOptions() {
        parent::resetOptions();
        $this->optionStack = new SplStack();
    }
}