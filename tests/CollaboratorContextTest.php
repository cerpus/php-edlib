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
     */
    public function testJsonSerialization()
    {
        $context = (new CollaboratorContext())
            ->setContext('something|more')
            ->addTenantId('Tenant 1')
            ->addTenantId('tenant 2')
            ->addResourceId('the resource id')
            ->addResourceId('more resources');

        $this->assertEquals(
            '{"context":"something|more","resourceIds":["the resource id","more resources"],"tenantIds":["Tenant 1","tenant 2"]}',
            json_encode($context)
        );
    }
}
