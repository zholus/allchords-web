<?php
declare(strict_types=1);

namespace App\SongsCatalog\Service;

use App\Http\HttpClient;
use App\SongsCatalog\Model\Song;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final class HttpSongService implements SongsService
{
    private ViewModelMapper $viewModelMapper;
    private LoggerInterface $logger;
    private HttpClient $httpClient;

    public function __construct(
        HttpClient $httpClient,
        ViewModelMapper $viewModelMapper,
        LoggerInterface $logger
    ) {
        $this->viewModelMapper = $viewModelMapper;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    /**
     * @return Song[]
     */
    public function getSongsByCreationDate(int $limit, ?DateTimeImmutable $date): array
    {
        try {
            $response = $this->httpClient->get('/api/songs-catalog/songs');

            $songs = json_decode($response->getContent(), true);

            $songs = $songs['songs'];
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception
            ]);

            $songs = [];
        }

        $result = [];

        foreach ($songs as $song) {
            $result[] = $this->viewModelMapper->mapSongs($song);
        }

        return $result;
    }
}
