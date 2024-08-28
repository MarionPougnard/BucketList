<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('main/index.html.twig', [
            'title' => "Ceci est la page d'accueil !",

        ]);
    }

    #[Route('/about-us', name: 'about')]
    public function aboutUs(): Response
    {
        $team = json_decode(file_get_contents('../data/team.json'));
        return $this->render('main/about.html.twig', [
            'title' => 'About us',
            'contenu' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            'team' => $team,

        ]);
    }
}
