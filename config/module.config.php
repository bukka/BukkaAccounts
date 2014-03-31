<?php
return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'BukkaAccounts\Controller\Accounts' => 'BukkaAccounts\Controller\AccountsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'accounts' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/accounts[/][:action][/:id]',
					'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'BukkaAccounts\Controller',
                        'controller'    => 'Accounts',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'accounts' => __DIR__ . '/../view',
        )
    ),
);
