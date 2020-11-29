<?php
declare(strict_types=1);

namespace App\SongsCatalog\Service;

use App\SongsCatalog\Model\Song;
use DateTimeImmutable;

interface SongsService
{
    /**
     * @return Song[]
     */
    public function getSongsByCreationDate(int $limit, ?DateTimeImmutable $date): array;
    //public function sendToReview(string $title, array $artistsIds, array $genresIds, string $chords): void;
}
