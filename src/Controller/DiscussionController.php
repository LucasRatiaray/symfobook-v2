<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Discussion;
use App\Form\CommentForm;
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
        return $this->render('discussion/index.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_discussion_show', methods: ['GET', 'POST'])]
    public function show(Discussion $discussion, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('error', 'You must be logged in to add a comment.');
                return $this->redirectToRoute('app_login');
            }

            if (in_array('ROLE_BANNED', $this->getUser()->getRoles())) {
                $this->addFlash('error', 'You are not allowed to add comments.');
                return $this->redirectToRoute('app_discussion_show', ['id' => $discussion->getId()]);
            }

            $comment->setDiscussion($discussion);
            $comment->setUser($this->getUser());
            $now = new \DateTimeImmutable();
            $comment->setCreatedAt($now);
            $comment->setUpdatedAt($now);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment has been added!');
            return $this->redirectToRoute('app_discussion_show', ['id' => $discussion->getId()]);
        }

        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
            'commentForm' => $form->createView(),
        ]);
    }
}
