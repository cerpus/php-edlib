<?php

namespace Cerpus\Edlib\ApiClient;

/**
 * Collaborator context, Context and TenantId are required.
 */
class CollaboratorContext implements \JsonSerializable
{
    use DtoTrait;

    private string $context = '';
    private array $resourceIds = [];
    private array $tenantIds = [];
    private ?array $externalResources = null;

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
        $self->resourceIds = array_unique($resourceIds, SORT_REGULAR);

        return $self;
    }

    /**
     * Add a resource id to the list of resource ids
     */
    public function addResourceId(string $resourceId): self
    {
        $self = clone $this;
        if (!in_array($resourceId, $self->resourceIds)) {
            $self->resourceIds[] = $resourceId;
        }

        return $self;
    }

    /**
     * Get the list of resource ids
     * @return string[]
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
        $self->tenantIds = array_unique($tenantIds, SORT_REGULAR);

        return $self;
    }

    /**
     * Add tenant to the list of tenant ids. Most likely a user id
     */
    public function addTenantId(string $tenantId): self
    {
        $self = clone $this;
        if (!in_array($tenantId, $self->tenantIds)) {
            $self->tenantIds[] = $tenantId;
        }

        return $self;
    }

    /**
     * Get the list of tenant ids
     * @return string[]
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
        $self->externalResources = array_unique($externalResources, SORT_REGULAR);

        return $self;
    }

    /**
     * Add item to the list of external resources. This should be avoided but is available for compatibility reasons
     */
    public function addExternalResource(string $systemName, string $resourceId): self
    {
        $self = clone $this;
        $newResource = [
            'systemName' => $systemName,
            'resourceId' => $resourceId,
        ];
        if (!is_array($self->externalResources) || !in_array($newResource, $self->externalResources, SORT_REGULAR)) {
            $self->externalResources[] = $newResource;
        }

        return $self;
    }

    /**
     * Get the list of external resources
     * @return array[]
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
