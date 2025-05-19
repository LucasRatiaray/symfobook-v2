<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Form\DiscussionForm;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/discussion')]
final class DiscussionController extends AbstractController
{
    #[Route(name: 'app_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findAllWithRelations();
        
        return $this->render('discussion/index.html.twig', [
            'discussions' => $discussions,
        ]);
    }

    #[Route('/{id}', name: 'app_discussion_show', methods: ['GET'])]
    public function show(Discussion $discussion, DiscussionRepository $discussionRepository): Response
    {
        $discussion = $discussionRepository->findOneWithAllRelations($discussion->getId());
        
        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }
}
