<?php

namespace Cerpus\Edlib\ApiClient;

use GuzzleHttp\Promise\PromiseInterface;
use InvalidArgumentException;

class EdlibApi
{
    /** @var EdlibApiClient $client */
    private $client;
    private $headers = [];

    public function __construct(EdlibApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set the application id and token
     * @see https://docs.edlib.com/docs/developers/api-documentation/application-api/authentication/
     *
     * @param string $apiApplicationId
     * @param string $apiToken
     */
    public function setCredentials(string $apiApplicationId, string $apiToken)
    {
        $this->headers = [
            'headers' => [
                'x-api-key' => $apiToken,
                'x-api-client-id' => $apiApplicationId,
            ],
        ];
    }

    /**
     * Set or update collaborator context
     * @see https://docs.edlib.com/docs/developers/api-documentation/application-api/collaborator-contexts/
     *
     * @param CollaboratorContext $context
     * @return PromiseInterface
     * @throws InvalidArgumentException
     */
    public function setCollaboratorContext(CollaboratorContext $context): PromiseInterface
    {
        if (!$context->isValid()) {
            throw new InvalidArgumentException('CollaboratorContext is not valid');
        }

        return $this->client->postAsync(
            '/common/app/context-resource-collaborators',
            array_merge(['json' => $context], $this->headers)
        );
    }

    /**
     * Generate H5P from QA
     * @see https://docs.edlib.com/docs/developers/api-documentation/application-api/generate-h5p-from-qa
     * 
     * @param array $body
     * @return PromiseInterface
     */
    public function generateH5pFromQa(array $body): PromiseInterface
    {
        return $this->client->postAsync(
            '/common/app/h5p/generate-from-qa',
            array_merge(['json' => $body], $this->headers)
        );
    }
}
