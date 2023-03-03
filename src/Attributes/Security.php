<?php

namespace NeeZiaa\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]

class Security {

    private string $requiredGroup;
    private string|array $requiredPermissions;

    public function __construct(string $requiredGroup = [], string|array $requiredPermissions = [], $authorizedCountries = [], $authorizedIp = [])
    {
        $this->requiredGroup = $requiredGroup;
        $this->requiredPermissions = $requiredPermissions;
    }

    /**
     * @return string
     */
    public function getRequiredGroup(): string
    {
        return $this->requiredGroup;
    }

    /**
     * @return array|string
     */
    public function getRequiredPermissions(): array|string
    {
        return $this->requiredPermissions;
    }

}