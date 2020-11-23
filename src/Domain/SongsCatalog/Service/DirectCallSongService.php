<?php
declare(strict_types=1);

namespace App\Domain\SongsCatalog\Service;

use App\Domain\SongsCatalog\ViewModel\Song;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final class DirectCallSongService implements SongsService
{
    private ViewModelMapper $viewModelMapper;
    private LoggerInterface $logger;

    public function __construct(
        ViewModelMapper $viewModelMapper,
        LoggerInterface $logger
    ) {
        $this->viewModelMapper = $viewModelMapper;
        $this->logger = $logger;
    }

    /**
     * @return Song[]
     */
    public function getSongsByCreatedDate(int $limit, ?DateTimeImmutable $date): array
    {
        try {
            $songsDto = $this->songContract->getSongsByCreatedDate($limit, $date);

            $songs = $songsDto->getSongs();
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception
            ]);

            $songs = [];
        }

        $result = [];

        foreach ($songs as $song) {
            $result[] = $this->viewModelMapper->mapSongsByCreatedDate($song);
        }

        return $result;
    }

    public function sendToReview(string $title, array $artistsIds, array $genresIds, string $chords): void
    {
        $songsDto = $this->songContract->getSongsByCreatedDate($limit, $date);

    }
}
