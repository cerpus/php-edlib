<?php

namespace Cerpus\Edlib\ApiClient;

/**
 * Collaborator context, Context and TenantId are required.
 */
class CollaboratorContext implements \JsonSerializable
{
    use DtoTrait;

    /**
     * @var string
     */
    private $context;
    /**
     * @var array
     */
    private $resourceIds = [];
    /**
     * @var array
     */
    private $tenantIds = [];
    /**
     * @var array
     */
    private $externalResources;

    /**
     * Context name
     */
    public function setContext(string $context): self
    {
        $self = clone $this;
        $self->context = $context;

        return $self;
    }

    /**
     * Get context name
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * Set list of resource ids
     */
    public function setResourceIds(array $resourceIds): self
    {
        $self = clone $this;
        $self->resourceIds = $resourceIds;

        return $self;
    }

    /**
     * Add a resource id to the list of resource ids
     */
    public function addResourceId(string $resourceId): self
    {
        $self = clone $this;
        $self->resourceIds[] = $resourceId;

        return $self;
    }

    /**
     * Get the list of resource ids
     */
    public function getResourceIds(): array
    {
        return $this->resourceIds;
    }

    /**
     * Set the list of tenant ids. Most likely those are user ids
     */
    public function setTenantIds(array $tenantIds): self
    {
        $self = clone $this;
        $self->tenantIds = $tenantIds;

        return $self;
    }

    /**
     * Add tenant to the list of tenant ids. Most likely a user id
     */
    public function addTenantId(string $tenantId): self
    {
        $self = clone $this;
        $self->tenantIds[] = $tenantId;

        return $self;
    }

    /**
     * Get the list of tenant ids
     */
    public function getTenantIds(): array
    {
        return $this->tenantIds;
    }

    /**
     * Set the list of external resources. This should be avoided but is available for compatibility reasons
     */
    public function setExternalResources(array $externalResources): self
    {
        $self = clone $this;
        $self->externalResources = $externalResources;

        return $self;
    }

    /**
     * Add item to the list of external resources. This should be avoided but is available for compatibility reasons
     */
    public function addExternalResource(string $systemName, string $resourceId): self
    {
        $self = clone $this;
        $self->externalResources[] = [
            'systemName' => $systemName,
            'resourceId' => $resourceId,
        ];

        return $self;
    }

    /**
     * Get the list of external resources
     */
    public function getExternalResources(): array
    {
        return $this->externalResources;
    }

    /**
     * Check that the minimum of required data is present
     */
    public function isValid(): bool
    {
        return (
            count($this->tenantIds) > 0 &&
            strlen($this->context) > 0
        );
    }
}
