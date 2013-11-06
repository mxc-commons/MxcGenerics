<?php

namespace MxcGenerics;

return array (
    'form_elements' => array (
		'invokables' => array(
			'my-form'        => 'MxcGenerics\Form\EventForm',
		),
    ),
    'hydrators' => array(
	   'invokables' => array(
    	   'classmethods'  => 'MxcGenerics\Stdlib\Hydrator\ClassMethods',
        ),
    ),
);
