<?php

namespace App\Services\Apple\Trailers;

use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class AppleTrailerDBService
{
    public function store(
        Movie $movie,
        EntityManagerInterface $doctrine
    ): string {
            $item = $doctrine->getRepository(Movie::class)->findOneBy(['title' => $movie->getTitle()]);

            if (!$item) {
                $doctrine->persist($movie);
                $doctrine->flush();
                return 'Create new Movie';
            }

            return  'Move found';
    }

    public function getAll(
        EntityManagerInterface $doctrine
    ): Collection  {
        $data = $doctrine->getRepository(Movie::class)->findAll();

        return new ArrayCollection($data);
    }

    public function show(
        int $id,
        EntityManagerInterface $doctrine
    ): Movie {
        return $doctrine->getRepository(Movie::class)->find($id);
    }
}