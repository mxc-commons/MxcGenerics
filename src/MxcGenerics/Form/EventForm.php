<?php

namespace MxcGenerics\Form;

use Zend\Form\FormInterface;

class EventForm extends EventProviderForm {
    
    const EVENT_PRE_BIND            = 'ef-pre-bind';
    const EVENT_PRE_PREPARE         = 'ef-pre-prepare';
    const EVENT_PRE_VALIDATE        = 'ef-pre-validate';
    //-- @todo: implement EVENT_POST_ELEMENT_CREATE
    const EVENT_POST_ELEMENT_CREATE = 'ef-post-element-create';
    
    /**
     * Bind an object to the element
     *
     * Allows populating the object with validated values.
     *
     * @overwrite
     * 
     * @param  object $object
     * @param  int $flags
     * @return mixed
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        $params = array('object' => $object, 'flags' => $flags);
        $this->getEventManager()->trigger(self::EVENT_PRE_BIND,$this,$params);
        return parent::bind($object, $flags);
    }
    
    /**
     * Validate the form
     *
     * Typically, will proxy to the composed input filter.
     *
     * @return bool
     * @throws Exception\DomainException
     */
    public function isValid()
    {
        if ($this->hasValidated) {
            return $this->isValid;
        }
        $this->getEventManager()->trigger(self::EVENT_PRE_VALIDATE,$this);
        return parent::isValid();
    }
        
    /**
     * Ensures state is ready for use
     *
     * Marshalls the input filter, to ensure validation error messages are
     * available, and prepares any elements and/or fieldsets that require
     * preparation.
     *
     * @return Form
     */
    public function prepare()
    {
        if ($this->isPrepared) {
            return $this;
        }
        $this->getEventManager()->trigger(self::EVENT_PRE_PREPARE,$this);
        return parent::prepare();
    }
}