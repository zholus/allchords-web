<?php
declare(strict_types=1);

namespace App\SongsReviews\Service;

use App\Accounts\Service\AuthService;
use App\Http\HttpClient;
use App\SongsReviews\Model\Artist;
use App\SongsReviews\Model\Genre;
use App\SongsReviews\Model\Pagination;
use DomainException;

final class HttpReviewService implements ReviewService
{
    private HttpClient $httpClient;
    private AuthService $authService;

    public function __construct(
        HttpClient $httpClient,
        AuthService $authService
    ) {
        $this->httpClient = $httpClient;
        $this->authService = $authService;
    }

    public function getArtistsPaginated(?string $artistTitle, int $limit, int $page): array
    {
        $response = $this->httpClient->get('/api/songs-reviews/artists', [
            'query' => [
                'artist_title' => $artistTitle,
                'limit' => $limit,
                'page' => $page,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authService->getUser()->getToken()->getAccessToken()
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);

        $artists = [];
        foreach ($responseData['artists'] as $artist) {
            $artists[] = new Artist($artist['artist_id'], $artist['artist_title']);
        }

        $pagination = new Pagination(
            $responseData['pagination']['total_elements_count'],
            $responseData['pagination']['current_page'],
            $responseData['pagination']['total_pages_count'],
            $responseData['pagination']['elements_on_page']
        );

        return [$artists, $pagination];
    }

    public function getGenresPaginated(?string $genreTitle, int $limit, int $page): array
    {
        $response = $this->httpClient->get('/api/songs-reviews/genres', [
            'query' => [
                'genre_title' => $genreTitle,
                'limit' => $limit,
                'page' => $page,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authService->getUser()->getToken()->getAccessToken()
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);

        $genres = [];
        foreach ($responseData['genres'] as $genre) {
            $genres[] = new Genre($genre['genre_id'], $genre['genre_title']);
        }

        $pagination = new Pagination(
            $responseData['pagination']['total_elements_count'],
            $responseData['pagination']['current_page'],
            $responseData['pagination']['total_pages_count'],
            $responseData['pagination']['elements_on_page']
        );

        return [$genres, $pagination];
    }

    public function newReview(
        string $creatorId,
        array $artistsIds,
        array $genresIds,
        string $title,
        string $chords
    ): void {
        $response = $this->httpClient->post('/api/songs-reviews/reviews', [
            'body' => [
                'title' => $title,
                'artists_ids' => $artistsIds,
                'genres_ids' => $genresIds,
                'chords' => $chords,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authService->getUser()->getToken()->getAccessToken()
            ],
        ]);

        if ($response->getStatusCode() >= 300) {
            throw new DomainException('Error during sending song to review');
        }
    }
}
