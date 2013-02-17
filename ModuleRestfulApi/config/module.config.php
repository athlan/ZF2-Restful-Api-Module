<?php
return array(
	'errors' => array(
		'show_exceptions' => array(
			'message' => true,
			'trace'   => false
		)
	),
  
  'moduleApi_postprocessor' => array(
    'formatters' => array('json', 'xml'),
  ),
  
	'di' => array(
		'instance' => array(
			'alias' => array(
				'moduleApi-postprocessor-json'  => 'ModuleRestfulApi\PostProcessor\Json',
				'moduleApi-postprocessor-xml'   => 'ModuleRestfulApi\PostProcessor\Xml',
				'moduleApi-postprocessor-image' => 'ModuleRestfulApi\PostProcessor\Image',
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'ModuleRestfulApi\Controller\TestError' => 'ModuleRestfulApi\Controller\TestErrorController',
		)
	),
    
    'router' => array(
        'routes' => array(
            'api' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'       => '/api/:controller[/:id][.:formatter]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'formatter'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[a-zA-Z0-9_-]*',
                        'api'        => '1',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'ModuleApi\Controller',
                    ),
                ),
            ),
        ),
    ),
);
