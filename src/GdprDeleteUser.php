<?php

namespace Cerpus\Edlib\ApiClient;

class GdprDeleteUser
{
    use DtoTrait;

    private string $requestId;
    private string $userId;
    private array $emails;

    public function __construct(string $requestId, string $userId, array $emails = [])
    {

        $this->requestId = $requestId;
        $this->userId = $userId;
        $this->emails = $emails;
    }
}
