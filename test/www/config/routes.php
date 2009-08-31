<?php


Midori\Application\Routes::draw(function($map){
	
		$map->addRoute(
			"default", 
			new Zend_Controller_Router_Route(
				':controller/:action/:id', 
    			array("id" => -1)
    		)
		);
		
		$map->addRoute(
			"default_no_param", 
			new Zend_Controller_Router_Route(
				':controller/:action/'
    		)
		);
		
		$map->addRoute(
			"default_index", 
			new Zend_Controller_Router_Route(
				':controller/'
    		)
		);
		
		$map->addRoute(
			"presspass",
			new Zend_Controller_Router_Route(
				'presspass/:id',
				   array(
			        'controller' => 'users',
			        'action'     => 'register-reporter'
			    )
			)
		);
		
		$map->addRoute(
			"forgotpassword",
			new Zend_Controller_Router_Route(
				'forgotpassword',
				   array(
			        'controller' => 'users',
			        'action'     => 'forgot-password'
			    )
			)
		);
		
		$map->addRoute(
			"forgotusername",
			new Zend_Controller_Router_Route(
				'forgotusername',
				   array(
			        'controller' => 'users',
			        'action'     => 'forgot-username'
			    )
			)
		);
		
		$map->addRoute(
			"search",
			new Zend_Controller_Router_Route(
				'search',
				   array(
			        'controller' => 'home',
			        'action'     => 'search'
			    )
			)
		);

		$map->addRoute(
			"contact",
			new Zend_Controller_Router_Route(
				'contact',
				   array(
			        'controller' => 'home',
			        'action'     => 'contact'
			    )
			)
		);		
		
		$map->addRoute(
			"register-contributor",
			new Zend_Controller_Router_Route(
				'register-contributor/:id',
				   array(
			        'controller' => 'users',
			        'action'     => 'become-a-pio'
			    )
			)
		);
		
		$map->addRoute(
			"register",
			new Zend_Controller_Router_Route(
				'register',
				   array(
			        'controller' => 'users',
			        'action'     => 'register'
			    )
			)
		);

		$map->addRoute(
			"libraries",
			new Zend_Controller_Router_Route(
				'libraries',
				   array(
			        'controller' => 'ee',
			        'action'     => 'libraries'
			    )
			)
		);

		$map->addRoute(
			"support",
			new Zend_Controller_Router_Route(
				'support',
				   array(
			        'controller' => 'ee',
			        'action'     => 'support'
			    )
			)
		);

		$map->addRoute(
			"resources",
			new Zend_Controller_Router_Route(
				'resources',
				   array(
			        'controller' => 'ee',
			        'action'     => 'resources'
			    )
			)
		);


		$map->addRoute(
			"about",
			new Zend_Controller_Router_Route(
				'about',
				   array(
			        'controller' => 'ee',
			        'action'     => 'about'
			    )
			)
		);		

		$map->addRoute(
			"privacy",
			new Zend_Controller_Router_Route(
				'privacy',
				   array(
			        'controller' => 'ee',
				    'action'	=>	'privacy'
			    )
			)
		);

		$map->addRoute(
			"terms-of-service",
			new Zend_Controller_Router_Route(
				'terms-of-service',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'termsOfService'
			    )
			)
		);		

		$map->addRoute(
			"contributors",
			new Zend_Controller_Router_Route(
				'contributors',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'contributors'
			    )
			)
		);

		$map->addRoute(
			"journalists",
			new Zend_Controller_Router_Route(
				'journalists',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'journalists'
			    )
			)
		);
		
		$map->addRoute(
			"public-users",
			new Zend_Controller_Router_Route(
				'public-users',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'public-users'
			    )
			)
		);

				
		$map->addRoute(
			"newswise-staff",
			new Zend_Controller_Router_Route(
				'newswise-staff',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'newswise-staff'
			    )
			)
		);

				
		$map->addRoute(
			"about-newswise",
			new Zend_Controller_Router_Route(
				'about-newswise',
				   array(
			        'controller' => 'ee',
				   	'action'	=>	'about-newswise'
			    )
			)
		);		
				
//		$map->addRoute(
//			"faq/C1",
//			new Zend_Controller_Router_Route(
//				'faq/C1',
//				   array(
//			        'controller' => 'ee',
//				   	'action'	=>	'faq-contributors'
//			    )
//			)
//		);
//				
//		$map->addRoute(
//			"faq/C2",
//			new Zend_Controller_Router_Route(
//				'faq/C2',
//				   array(
//			        'controller' => 'ee',
//				   	'action'	=>	'faq-journalists'
//			    )
//			)
//		);
//
//						
//		$map->addRoute(
//			"faq/C3",
//			new Zend_Controller_Router_Route(
//				'faq/C3',
//				   array(
//			        'controller' => 'ee',
//				   	'action'	=>	'faq-public-users'
//			    )
//			)
//		);
		
//		$map->addRoute(
//			"thematic-wires",
//			new Zend_Controller_Router_Route(
//				'thematic-wires',
//				   array(
//			        'controller' => 'ee',
//				   	'action'	=>	'thematic-wires'
//			    )
//			)
//		);		
});
?>