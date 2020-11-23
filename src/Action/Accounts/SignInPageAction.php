<?php
declare(strict_types=1);

namespace App\Action\Accounts;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SignInPageAction extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('accounts/sign_in.twig');
    }
}
