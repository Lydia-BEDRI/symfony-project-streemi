<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: MovieRepository::class)]
class Movie extends Media
{
    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => null])] // Duration peut être null
    private ?int $duration = 120; // Valeur par défaut 2h (120 minutes)


    #[ORM\Column(type: 'json')]
    private array $trailers = [];


    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTrailers(): array
    {
        return $this->trailers;
    }

    public function setTrailers(array $trailers): static
    {
        $this->trailers = $trailers;

        return $this;
    }
}
