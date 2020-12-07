<?php
declare(strict_types=1);

namespace App\SongsReviews\Service;

interface ReviewService
{
    public function getArtistsPaginated(?string $artistTitle, int $limit, int $page): array;
    public function getGenresPaginated(?string $genreTitle, int $limit, int $page): array;
    public function newReview(
        string $creatorId,
        array $artistsIds,
        array $genresIds,
        string $title,
        string $chords
    ): void;
}
