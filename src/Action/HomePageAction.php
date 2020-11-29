<?php
declare(strict_types=1);

namespace App\Action;

use App\SongsCatalog\Service\SongsService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomePageAction extends AbstractController
{
    private SongsService $songs;

    public function __construct(SongsService $songs)
    {
        $this->songs = $songs;
    }

    public function __invoke(): Response
    {
        $songs = $this->songs->getSongsByCreationDate(3, new DateTimeImmutable());

        return $this->render('home_page.twig', [
            'songs' => $songs
        ]);
    }
}
