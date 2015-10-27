<?php

namespace Adldap\tests\Connections;

use Adldap\Connections\Configuration;
use Adldap\Connections\Ldap;
use Adldap\Connections\Manager;
use Adldap\Tests\UnitTestCase;

class ManagerTest extends UnitTestCase
{
    public function testConstruct()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Connections\ConnectionInterface', $m->getConnection());
        $this->assertInstanceOf('Adldap\Connections\Configuration', $m->getConfiguration());
    }

    public function testAuthUsernameFailure()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->setExpectedException('Adldap\Exceptions\Auth\UsernameRequiredException');

        $m->authenticate(' ', 'password');
    }

    public function testAuthPasswordFailure()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->setExpectedException('Adldap\Exceptions\Auth\PasswordRequiredException');

        $m->authenticate('username', ' ');
    }

    public function testAuthFailureException()
    {
        $connection = $this->newConnectionMock();

        $connection->shouldReceive('connect')->once()->andReturn(true);
        $connection->shouldReceive('setOption')->twice()->andReturn(true);
        $connection->shouldReceive('isUsingSSL')->once()->andReturn(false);
        $connection->shouldReceive('bind')->once()->withArgs(['username', 'password'])->andReturn(false);
        $connection->shouldReceive('getLastError')->once()->andReturn('');
        $connection->shouldReceive('isBound')->once()->andReturn(true);
        $connection->shouldReceive('close')->once()->andReturn(true);

        $m = new Manager($connection, new Configuration());

        $this->assertFalse($m->authenticate('username', 'password'));
    }

    public function testGroups()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Groups', $m->groups());
    }

    public function testUsers()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Users', $m->users());
    }

    public function testContainers()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Containers', $m->containers());
    }

    public function testContacts()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Contacts', $m->contacts());
    }

    public function testExchange()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Exchange', $m->exchange());
    }

    public function testComputers()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Computers', $m->computers());
    }

    public function testSearch()
    {
        $m = new Manager(new Ldap(), new Configuration());

        $this->assertInstanceOf('Adldap\Classes\Search', $m->search());
    }
}