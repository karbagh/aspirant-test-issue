<?php

namespace App\Services\Apple\Trailers;

use App\Dto\Apple\Responses\AppleClientNewTrailersResponseDto;
use App\Services\BaseClientService;

class AppleTrailerClientService extends BaseClientService
{
    public function __construct()
    {
        $this->host = 'https://trailers.apple.com/trailers/home/rss';
    }

    public function getTrailers(): AppleClientNewTrailersResponseDto {
        $endpoint = '/newtrailers.rss';
        $response = $this->get($endpoint, [
            'Accept' => 'text/xml'
        ]);

        return new AppleClientNewTrailersResponseDto(
            $endpoint,
            $response->getBody()->getContents(),
        );
    }
}