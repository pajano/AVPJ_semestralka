<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/[:action][/:login][/:pass]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'room' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/room[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Room',
                        'action'     => 'list',
                    ),
                ),
            ),
            'person' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/person[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Person',
                        'action'     => 'list',
                    ),
                ),
            ),
            'accesscodes' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/accesscodes[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\AccessCode',
                        'action'     => 'list',
                    ),
                ),
            ),
            'administration' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/administration[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'index',
                    ),
                ),
            ),
            'accessright' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/accessrights[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\AccessRight',
                        'action'     => 'list',
                    ),
                ),
            ),
            'accesslog' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/accesslog[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\AccessLog',
                        'action'     => 'list',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class,
            'Application\Controller\Room' => Controller\RoomController::class,
            'Application\Controller\Person' => Controller\PersonController::class,
            'Application\Controller\AccessCode' => Controller\AccessCodeController::class,
            'Application\Controller\Administration' => Controller\AdministrationController::class,
            'Application\Controller\AccessRight' => Controller\AccessRightController::class,
            'Application\Controller\AccessLog' => Controller\AccessLogController::class,
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'dataAccess' => 'Application\Controller\Plugin\DataAccessPlugin',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formHelper' => 'Application\Helper\FormHelper',
            'layoutHelper' => 'Application\Helper\LayoutHelper',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
