<?php

namespace Cerpus\Edlib\ApiClient;

use GuzzleHttp\Promise\PromiseInterface;
use InvalidArgumentException;

class EdlibApi
{
    /** @var EdlibApiClient $client */
    private $client;

    public function __construct(EdlibApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set or update collaborator context
     * @see https://docs.edlib.com/docs/developers/api-documentation/application-api/collaborator-contexts/
     *
     * @param CollaboratorContext $context
     * @param string $apiToken
     * @param string $apiApplicationId
     * @return PromiseInterface
     * @throws InvalidArgumentException
     */
    public function setCollaboratorContext(CollaboratorContext $context, string $apiApplicationId, string $apiToken): PromiseInterface
    {
        if (!$context->isValid()) {
            throw new InvalidArgumentException('CollaboratorContext is not valid');
        }

        return $this->client->postAsync(
            '/common/app/context-resource-collaborators',
            [
                'json' => $context,
                'headers' => [
                    'x-api-key' => $apiToken,
                    'x-api-client-id' => $apiApplicationId,
                ],
            ]
        );
    }

    /**
     * Generate H5P from QA
     * @see https://docs.edlib.com/docs/developers/api-documentation/application-api/generate-h5p-from-qa
     * 
     * @param array $body
     * @param string $apiToken
     * @param string $apiApplicationId
     * @return PromiseInterface
     */
    public function generateH5pFromQa(array $body, string $apiApplicationId, string $apiToken): PromiseInterface
    {
        return $this->client->postAsync(
            '/common/app/h5p/generate-from-qa',
            [
                'json' => $body,
                'headers' => [
                    'x-api-key' => $apiToken,
                    'x-api-client-id' => $apiApplicationId,
                ],
            ]
        );
    }
}
