<?php

namespace App\Dto\Apple\Responses;

use App\Dto\BaseDto;

class AppleClientNewTrailersResponseDto extends BaseDto
{
    public function __construct(
        private string $endpoint,
        private string $data
    ){}

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'endpoint' => $this->getEndpoint(),
            'data' => $this->getData(),
        ];
    }
}