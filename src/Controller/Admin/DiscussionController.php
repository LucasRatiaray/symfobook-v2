<?php

namespace App\Controller\Admin;

use App\Entity\Discussion;
use App\Form\DiscussionForm;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/discussion')]
final class DiscussionController extends AbstractController
{
    #[Route(name: 'app_admin_discussion_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        return $this->render('admin/discussion/index.html.twig', [
            'discussions' => $discussionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_discussion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();
        $form = $this->createForm(DiscussionForm::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($discussion);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/discussion/new.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_discussion_show', methods: ['GET'])]
    public function show(Discussion $discussion): Response
    {
        return $this->render('admin/discussion/show.html.twig', [
            'discussion' => $discussion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_discussion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DiscussionForm::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/discussion/edit.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_discussion_delete', methods: ['POST'])]
    public function delete(Request $request, Discussion $discussion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$discussion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_discussion_index', [], Response::HTTP_SEE_OTHER);
    }
}
