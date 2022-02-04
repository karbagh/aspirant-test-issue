<?php

namespace App\Services\Apple\Trailers;

use App\Entity\Movie;
use DateTime;
use Exception;
use App\Services\BaseXmlParserService;
use SimpleXMLElement;
use function GuzzleHttp\Psr7\str;

class AppleTrailerXmlParserService extends BaseXmlParserService
{
    /**
     * @throws Exception
     */
    public function parse(
        string $data
    ): array {
        try {
            $body = (new SimpleXMLElement($data))->children();

            if (!$this->isValid($body)) {
                throw new Exception('Could not find \'channel\' element in feed', 404);
            }

            return $this->collector($body->channel->item);
        }catch (Exception $e) {
            throw new Exception('Could not find \'channel\' element in feed', $e->getCode());
        }
    }

    private function collector(
        SimpleXMLElement $trailers
    ): array {
        $index = 1;
        $collection = [];
        foreach ($trailers as $trailer) {
            if ($index <= 10) {
                $movie = new Movie();
                $movie->setTitle((string) $trailer->title);
                $movie->setLink((string) $trailer->link);
                $movie->setDescription((string) $trailer->description);
                $movie->setPubDate(new DateTime($trailer->pubDate));
                $movie->setImage($this->getImage($trailer->children('content', true)->encoded->asXML()));

                array_push($collection, $movie);
            }
            $index++;
        }
        return $collection;
    }

    private function getImage($string): ?string {
        preg_match('/(?<=<img src=")[^"]+(?=")/', $string, $matches);
        return $matches[0] ?? null;
    }

    private function isValid(
        SimpleXMLElement $body
    ): bool {
        if (!$body->channel->count()) {
            return false;
        }
        if (!$body->channel->item[0]->count()) {
            return false;
        }

        return true;
    }
}