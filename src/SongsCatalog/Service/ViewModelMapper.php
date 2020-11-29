<?php
declare(strict_types=1);

namespace App\SongsCatalog\Service;

use App\SongsCatalog\Model\Song;
use DateTimeImmutable;

class ViewModelMapper
{
    public function mapSongs(array $songResponse): Song
    {
        return new Song(
            $songResponse['song_id'],
            $songResponse['artist_id'],
            $songResponse['artist_name'],
            $songResponse['title'],
            new DateTimeImmutable($songResponse['created_at']),
        );
    }
}
