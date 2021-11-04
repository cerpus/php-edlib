<?php

namespace Cerpus\Edlib\ApiClient\Tests;

use Cerpus\Edlib\ApiClient\CollaboratorContext;
use PHPUnit\Framework\TestCase;

class CollaboratorContextTest extends TestCase
{
    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::getContext
     */
    public function testContext()
    {
        $context = (new CollaboratorContext())->setContext('The Context');
        $this->assertEquals('The Context', $context->getContext());
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setResourceIds
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::getResourceIds
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addResourceId
     */
    public function testResourceId()
    {
        $context = (new CollaboratorContext())->setResourceIds(['resource 1', 'resource 2']);
        $this->assertEquals(['resource 1', 'resource 2'], $context->getResourceIds());

        $context = $context->addResourceId('resource 3');
        $this->assertEquals(['resource 1', 'resource 2', 'resource 3'], $context->getResourceIds());
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setTenantIds
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::getTenantIds
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     */
    public function testTenantsId()
    {
        $tenants = ['Tenant 1', 'Tenant 2'];
        $context = (new CollaboratorContext())->setTenantIds($tenants);
        $this->assertEquals($tenants, $context->getTenantIds());

        $context = $context->addTenantId('Tenant 3');
        $this->assertEquals(array_merge($tenants, ['Tenant 3']), $context->getTenantIds());
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setExternalResources
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::getExternalResources
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addExternalResource
     */
    public function testExternalResources()
    {
        $resources = [
            [
                'systemName' => 'system 1',
                'resourceId' => 'resource 1',
            ],
            [
                'systemName' => 'system 2',
                'resourceId' => 'resource 2',
            ]
        ];
        $context = (new CollaboratorContext())->setExternalResources($resources);
        $this->assertEquals($resources, $context->getExternalResources());

        $context = $context->addExternalResource('system 3', 'resource 3');
        $this->assertEquals(
            array_merge($resources, [['systemName' => 'system 3','resourceId' => 'resource 3']]),
            $context->getExternalResources()
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setTenantIds
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::isValid
     */
    public function testIsValid()
    {
        $tenants = ['Tenant 1', 'Tenant 2'];

        $context = new CollaboratorContext();
        $this->assertFalse($context->isValid());

        $context = $context->setContext('The context');
        $this->assertFalse($context->isValid());

        $context = $context->setContext('')->setTenantIds($tenants);
        $this->assertFalse($context->isValid());

        $context = $context->setContext('The context again');
        $this->assertTrue($context->isValid());
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addResourceId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addExternalResource
     */
    public function testJsonSerialization()
    {
        $context = (new CollaboratorContext());

        $this->assertEquals(
            '{"context":"","resourceIds":[],"tenantIds":[]}',
            json_encode($context)
        );

        $context = $context
            ->setContext('something|more');

        $this->assertEquals(
            '{"context":"something|more","resourceIds":[],"tenantIds":[]}',
            json_encode($context)
        );

        $context = $context
            ->addTenantId('Tenant 1')
            ->addTenantId('tenant 2');

        $this->assertEquals(
            '{"context":"something|more","resourceIds":[],"tenantIds":["Tenant 1","tenant 2"]}',
            json_encode($context)
        );

        $context = $context
            ->addResourceId('the resource id')
            ->addResourceId('more resources');

        $this->assertEquals(
            '{"context":"something|more","resourceIds":["the resource id","more resources"],"tenantIds":["Tenant 1","tenant 2"]}',
            json_encode($context)
        );

        $context = $context
            ->addExternalResource('unittest', 'another id')
            ->addExternalResource('testing', 'more ids');

        $this->assertEquals(
            '{"context":"something|more","resourceIds":["the resource id","more resources"],"tenantIds":["Tenant 1","tenant 2"],"externalResources":[{"systemName":"unittest","resourceId":"another id"},{"systemName":"testing","resourceId":"more ids"}]}',
            json_encode($context)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addExternalResource
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setExternalResources
     */
    public function testAddExternalResourcesEqualsSetExternalResources()
    {
        $baseContext = (new CollaboratorContext())->setContext('the|context');

        $add = $baseContext
            ->addExternalResource('system name', 'resource id')
            ->addExternalResource('system name', 'different id')
            ->addExternalResource('system_name', 'different id')
            ->addExternalResource('system name', 'different id');

        $set = $baseContext
            ->setExternalResources([
                [
                    'systemName' => 'system name',
                    'resourceId' => 'resource id',
                ],[
                    'systemName' => 'system name',
                    'resourceId' => 'different id',
                ],[
                    'systemName' => 'system_name',
                    'resourceId' => 'different id',
                ],[
                    'systemName' => 'system name',
                    'resourceId' => 'different id',
                ],
            ]);

        $this->assertEquals(
            json_encode($add),
            json_encode($set)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setTenantIds
     */
    public function testAddTenantIdEqualsSetTenantIds()
    {
        $baseContext = (new CollaboratorContext())->setContext('the|context');

        $add = $baseContext
            ->addTenantId('Tenant 1')
            ->addTenantId('Tenant 2')
            ->addTenantId('tenant 1')
            ->addTenantId('Tenant 1');

        $set = $baseContext
            ->setTenantIds([
                'Tenant 1',
                'Tenant 2',
                'tenant 1',
                'Tenant 1',
            ]);

        $this->assertEquals(
            json_encode($add),
            json_encode($set)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addResourceId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setResourceIds
     */
    public function testAddResourceIdEqualsSetResourceIds()
    {
        $baseContext = (new CollaboratorContext())->setContext('the|context');

        $add = $baseContext
            ->addResourceId('Resource 1')
            ->addResourceId('Resource 2')
            ->addResourceId('resource 1')
            ->addResourceId('Resource 1');

        $set = $baseContext
            ->setResourceIds([
                'Resource 1',
                'Resource 2',
                'resource 1',
                'Resource 1',
            ]);

        $this->assertEquals(
            json_encode($add),
            json_encode($set)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addExternalResource
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setExternalResources
     */
    public function testExternalResourceUnique()
    {
        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->addTenantId('Tenant 1')
            ->addExternalResource('system name', 'resource id')
            ->addExternalResource('system name', 'different id')
            ->addExternalResource('name of system', 'different id')
            ->addExternalResource('system name', 'resource id');

        $this->assertEquals(
            '{"context":"the|context","resourceIds":[],"tenantIds":["Tenant 1"],"externalResources":[{"systemName":"system name","resourceId":"resource id"},{"systemName":"system name","resourceId":"different id"},{"systemName":"name of system","resourceId":"different id"}]}',
            json_encode($context)
        );

        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->addTenantId('Tenant 1')
            ->setExternalResources([
                ['systemName' => 'system name', 'resourceId' => 'resource id'],
                ['systemName' => 'system name', 'resourceId' => 'different id'],
                ['systemName' => 'name of system', 'resourceId' => 'different id'],
                ['systemName' => 'system name', 'resourceId' => 'resource id'],
            ]);

        $this->assertEquals(
            '{"context":"the|context","resourceIds":[],"tenantIds":["Tenant 1"],"externalResources":[{"systemName":"system name","resourceId":"resource id"},{"systemName":"system name","resourceId":"different id"},{"systemName":"name of system","resourceId":"different id"}]}',
            json_encode($context)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setTenantIds
     */
    public function testTenantIdUnique()
    {
        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->addTenantId('Tenant 1')
            ->addTenantId('Tenant 2')
            ->addTenantId('tenant 2')
            ->addTenantId('Tenant 1');

        $this->assertEquals(
            '{"context":"the|context","resourceIds":[],"tenantIds":["Tenant 1","Tenant 2","tenant 2"]}',
            json_encode($context)
        );

        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->setTenantIds([
                'Tenant 1',
                'Tenant 2',
                'tenant 2',
                'Tenant 1',
            ]);

        $this->assertEquals(
            '{"context":"the|context","resourceIds":[],"tenantIds":["Tenant 1","Tenant 2","tenant 2"]}',
            json_encode($context)
        );
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::jsonSerialize
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addResourceId
     */
    public function testAddResourceIdUnique()
    {
        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->addTenantId('Tenant 1')
            ->addResourceId('First resource')
            ->addResourceId('second resource')
            ->addResourceId('Second resource')
            ->addResourceId('First resource');

        $this->assertEquals(
            '{"context":"the|context","resourceIds":["First resource","second resource","Second resource"],"tenantIds":["Tenant 1"]}',
            json_encode($context)
        );

        $context = (new CollaboratorContext())
            ->setContext('the|context')
            ->addTenantId('Tenant 1')
            ->setResourceIds([
                'First resource',
                'second resource',
                'Second resource',
                'First resource',
        ]);

        $this->assertEquals(
            '{"context":"the|context","resourceIds":["First resource","second resource","Second resource"],"tenantIds":["Tenant 1"]}',
            json_encode($context)
        );
    }
}
