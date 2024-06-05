<?php

namespace App\Controller;

use App\Entity\Todolist;
use App\Form\TodolistType;
use App\Repository\TodolistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/list', name: 'app_list')]
#[IsGranted('ROLE_USER')]
class ListController extends AbstractController
{
  #[Route('/', name: 'app_list_index', methods: ['GET'])]
  public function browse(TodolistRepository $todolistRepository): Response
  {
    if (!$this->getUser()) return $this->redirectToRoute('app_login');

    return $this->render('list/index.html.twig', [
      'todolists' => $todolistRepository->findByUser($this->getUser())
    ]);
  }

  #[Route('/new', name: 'app_list_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
  {
    $todolist = new Todolist();
    $todolist->setUser($security->getUser());
    $form = $this->createForm(TodolistType::class, $todolist);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($todolist);
      $entityManager->flush();

      return $this->redirectToRoute('app_list_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('list/new.html.twig', [
      'todolist' => $todolist,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_list_show', methods: ['GET'])]
  public function show(Todolist $todolist): Response
  {
    return $this->render('list/show.html.twig', [
      'todolist' => $todolist,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_list_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Todolist $todolist, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(TodolistType::class, $todolist);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_list_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('list/edit.html.twig', [
      'todolist' => $todolist,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_list_delete', methods: ['POST'])]
  public function delete(Request $request, Todolist $todolist, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $todolist->getId(), $request->getPayload()->get('_token'))) {
      $entityManager->remove($todolist);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_list_index', [], Response::HTTP_SEE_OTHER);
  }
}
