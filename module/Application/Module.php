<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Application\Model\Person;
use Application\Model\PersonTable;
use Application\Model\AccessCode;
use Application\Model\AccessCodeTable;
use Application\Model\AccessLog;
use Application\Model\AccessLogTable;
use Application\Model\AccessRight;
use Application\Model\AccessRightTable;
use Application\Model\Room;
use Application\Model\RoomTable;
use Application\Model\Administrator;
use Application\Model\AdministratorTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\PersonTable' => function($sm) {
                    $tableGateway = $sm->get('PersonTableGateway');
                    $table = new PersonTable($tableGateway);
                    return $table;
                },
                'PersonTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new Person());
                    return new TableGateway('personel', $dbAdapter, null, $resultSet);
                },
                'Application\Model\AccessCodeTable' => function($sm) {
                    $tableGateway = $sm->get('AccessCodeTableGateway');
                    $table = new AccessCodeTable($tableGateway);
                    return $table;
                },
                'AccessCodeTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new AccessCode());
                    return new TableGateway('accesscodes', $dbAdapter, null, $resultSet);
                },
                'Application\Model\AccessLogTable' => function($sm) {
                    $tableGateway = $sm->get('AccessLogTableGateway');
                    $table = new AccessLogTable($tableGateway);
                    return $table;
                },
                'AccessLogTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new AccessLog());
                    return new TableGateway('accesslogs', $dbAdapter, null, $resultSet);
                },
                'Application\Model\AccessRightTable' => function($sm) {
                    $tableGateway = $sm->get('AccessRightTableGateway');
                    $table = new AccessRightTable($tableGateway);
                    return $table;
                },
                'AccessRightTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new AccessRight());
                    return new TableGateway('accessrights', $dbAdapter, null, $resultSet);
                },
                'Application\Model\RoomTable' => function($sm) {
                    $tableGateway = $sm->get('RoomTableGateway');
                    $table = new RoomTable($tableGateway);
                    return $table;
                },
                'RoomTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new Room());
                    return new TableGateway('rooms', $dbAdapter, null, $resultSet);
                },
                'Application\Model\AdministratorTable' => function($sm) {
                    $tableGateway = $sm->get('AdministratorTableGateway');
                    $table = new AdministratorTable($tableGateway);
                    return $table;
                },
                'AdministratorTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSet = new ResultSet();
                    $resultSet->setArrayObjectPrototype(new Administrator());
                    return new TableGateway('administrator', $dbAdapter, null, $resultSet);
                },
            ),
        );
    }
}
