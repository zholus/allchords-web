<?php
declare(strict_types=1);

namespace App\Domain\SongsReviews\Service;


final class DirectCallReviewService implements ReviewService
{

    public function __construct(
    ) {
        $this->reviewsContract = $reviewsContract;
        $this->artistsContract = $artistsContract;
        $this->genresContract = $genresContract;
    }

    public function getArtistsPaginated(?string $artistTitle, int $limit, int $page): array
    {
        $paginatedResult = $this->artistsContract->getArtistsPaginated($artistTitle, $limit, $page);

        $artists = [];
        foreach ($paginatedResult->getArtistsDto() as $artist) {
            $artists[] = new ArtistDto($artist->getId(), $artist->getTitle());
        }

        $pagination = new PaginationDto(
            $paginatedResult->getPaginationDto()->getTotalElementsCount(),
            $paginatedResult->getPaginationDto()->getCurrentPage(),
            $paginatedResult->getPaginationDto()->getTotalPagesCount(),
            $paginatedResult->getPaginationDto()->getElementsOnPage()
        );

        return [$artists, $pagination];
    }

    public function getGenresPaginated(?string $genreTitle, int $limit, int $page): array
    {
        $paginatedResult = $this->genresContract->getGenresPaginated($genreTitle, $limit, $page);

        $genres = [];
        foreach ($paginatedResult->getGenresDto() as $genre) {
            $genres[] = new GenreDto($genre->getId(), $genre->getTitle());
        }

        $pagination = new PaginationDto(
            $paginatedResult->getPaginationDto()->getTotalElementsCount(),
            $paginatedResult->getPaginationDto()->getCurrentPage(),
            $paginatedResult->getPaginationDto()->getTotalPagesCount(),
            $paginatedResult->getPaginationDto()->getElementsOnPage()
        );

        return [$genres, $pagination];
    }
}
