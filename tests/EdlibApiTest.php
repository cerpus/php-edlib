<?php

namespace Cerpus\Edlib\ApiClient\Tests;

use Cerpus\Edlib\ApiClient\CollaboratorContext;
use Cerpus\Edlib\ApiClient\EdlibApi;
use Cerpus\Edlib\ApiClient\EdlibApiClient;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EdlibApiTest extends TestCase
{
    /** @var EdlibApiClient */
    private $client;
    /** @var EdlibApi  */
    private $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(EdlibApiClient::class);
        $this->service = new EdlibApi($this->client);
        parent::setUp();
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::__construct
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::setCollaboratorContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::isValid
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::setContext
     */
    public function testSetCollaboratorContext()
    {
        $context = (new CollaboratorContext())
            ->setContext('context')
            ->addTenantId('something');

        $this->client
            ->expects($this->once())
            ->method('postAsync')
            ->with(
                '/common/app/context-resource-collaborators',
                [
                    'json' => $context,
                    'headers' => [
                        'x-api-key' => 'theKey',
                        'x-api-client-id' => 'clientId',
                    ],
                ]
            )
            ->willReturn(Create::promiseFor(new Response(200, [], '{}')));

        $this->service->setCollaboratorContext($context, 'clientId', 'theKey')->wait();
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::__construct
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::setCollaboratorContext
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addResourceId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::addTenantId
     * @covers \Cerpus\Edlib\ApiClient\CollaboratorContext::isValid
     */
    public function testSetCollaboratorContextWithInvalidContext()
    {
        $context = (new CollaboratorContext())
            ->addResourceId('resource')
            ->addTenantId('something');

        $this->client
            ->expects($this->never())
            ->method('postAsync');

        $this->expectException(InvalidArgumentException::class);
        $this->service->setCollaboratorContext($context, 'theKey', 'clientId')->wait();
    }

    /**
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::__construct
     * @covers \Cerpus\Edlib\ApiClient\EdlibApi::generateH5pFromQa
     */
    public function testGenerateH5pFromQa()
    {
        $request = [
            "title" => "This is the title",
            "sharing" => true,
            "published" => true,
            "license" => "by",
            "authId" => "1",
            "questions" => [
                [
                    "type" => "H5P.MultiChoice",
                    "text" => "test",
                    "answers" => [
                        [
                            "text" => "w",
                            "correct" => false,
                        ], [
                            "text" => "w2",
                            "correct" => true,
                        ]
                    ],
                ],
            ],
        ];
        
        $response = [
            "resource" => [
                "id" => "7d9a225d-f155-45e4-9db7-01c6a0d643af",
                "resourceGroupId" => "3c9d9e2e-6023-4724-8abd-b5718ff04b90",
                "deletedReason" => null,
                "deletedAt" => null,
                "updatedAt" => "2021-09-30T07:47:29.000Z",
                "createdAt" => "2021-09-30T07:47:29.000Z",
                "version" => [
                    "id" => "aef52273-dfa6-46f6-bf42-a5497339c667",
                    "resourceId" => "7d9a225d-f155-45e4-9db7-01c6a0d643af",
                    "externalSystemName" => "contentauthor",
                    "externalSystemId" => "51",
                    "title" => "This is the title",
                    "description" => null,
                    "isPublished" => true,
                    "isListed" => true,
                    "isDraft" => false,
                    "license" => "by",
                    "language" => true,
                    "contentType" => "h5p.questionset",
                    "ownerId" => "1",
                    "maxScore" => null,
                    "authorOverwrite" => null,
                    "updatedAt" => "2021-09-30T07:47:29.000Z",
                    "createdAt" => "2021-09-30T07:47:29.000Z"
                ],
            ],
            "usageId" => "f1c5174f-fc6e-44cd-a95d-a9f9ee862e95"
        ];

        $this->client
            ->expects($this->once())
            ->method('postAsync')
            ->with(
                '/common/app/h5p/generate-from-qa',
                [
                    'json' => $request,
                    'headers' => [
                        'x-api-key' => 'theKey',
                        'x-api-client-id' => 'clientId',
                    ],
                ]
            )
            ->willReturn(Create::promiseFor(new Response(200, [], json_encode($response))));

        $this->service->generateH5pFromQa($request, 'clientId', 'theKey')->wait();
    }
}
