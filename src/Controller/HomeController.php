<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\DiscussionRepository;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository, UserRepository $userRepository, DiscussionRepository $discussionRepository): Response
    {
        $latestBooks = $bookRepository->findBy([], ['id' => 'DESC'], 3);

        $countBooks = $bookRepository->count([]);
        $countBooks = $countBooks > 0 ? $countBooks : 0;

        $countUsers = $userRepository->count([]);
        $countUsers = $countUsers > 0 ? $countUsers : 0;

        $countDiscussions = $discussionRepository->count([]);
        $countDiscussions = $countDiscussions > 0 ? $countDiscussions : 0;

        return $this->render('home/index.html.twig', [
            'latestBooks' => $latestBooks,
            'countBooks' => $countBooks,
            'countUsers' => $countUsers,
            'countDiscussions' => $countDiscussions,
        ]);
    }
}
