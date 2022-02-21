<?php

namespace Cerpus\Edlib\ApiClient;

use InvalidArgumentException;

class ContentAuthorQuestionAndAnswers implements \JsonSerializable
{
    use DtoTrait;

    private array $contexts;
    private string $userId;

    public function __construct(array $contexts, string $userId)
    {
        if (empty($contexts) || empty($userId)) {
            throw new InvalidArgumentException('contexts and userId must not be empty');
        }

        $this->contexts = $contexts;
        $this->userId = $userId;
    }
}
