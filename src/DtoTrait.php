<?php

namespace Cerpus\Edlib\ApiClient;

trait DtoTrait
{
    public function jsonSerialize(): array
    {
        return \array_filter(\get_object_vars($this), function ($v) {
            return $v !== null;
        });
    }
}
