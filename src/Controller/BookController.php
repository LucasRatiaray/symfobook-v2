<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Discussion;
use App\Form\DiscussionForm;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET', 'POST'])]
    public function show(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();

        $form = $this->createForm(DiscussionForm::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('error', 'You must be logged in to start a discussion.');
                return $this->redirectToRoute('app_login');
            }

            if (in_array('ROLE_BANNED', $this->getUser()->getRoles())) {
                $this->addFlash('error', 'You are not allowed to start discussions.');
                return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
            }

            $discussion->setBook($book);
            $discussion->setUser($this->getUser());
            $now = new \DateTimeImmutable();
            $discussion->setCreatedAt($now);
            $discussion->setUpdatedAt($now);

            $entityManager->persist($discussion);
            $entityManager->flush();

            $this->addFlash('success', 'Your discussion has been created!');
            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'discussionForm' => $form->createView(),
        ]);
    }
}
