<?php

namespace App\Controller\Table;

use App\Entity\Table\Table;
use App\Form\Table\TableType;
use App\Repository\TableRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/table")
 * @Security("is_granted('ROLE_USER')")
 */
class TableController extends AbstractController
{
    /**
     * @Route("/", name="app_table_index", methods={"GET"})
     * @param TableRepository $tableRepository
     *
     * @return Response
     */
    public function index(TableRepository $tableRepository): Response
    {
        return $this->render('table/index.html.twig', [
            'tables' => $tableRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_table_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $table = new Table();
        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($table);
            $entityManager->flush();

            return $this->redirectToRoute('app_table_index');
        }

        return $this->render('table/new.html.twig', [
            'table' => $table,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_table_show", methods={"GET"})
     * @param Table $table
     *
     * @return Response
     */
    public function show(Table $table): Response
    {
        return $this->render('table/show.html.twig', [
            'table' => $table,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_table_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Table   $table
     *
     * @return Response
     */
    public function edit(Request $request, Table $table): Response
    {
        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_table_index');
        }

        return $this->render('table/edit.html.twig', [
            'table' => $table,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_table_delete", methods={"DELETE"})
     * @param Request $request
     * @param Table   $table
     *
     * @return Response
     */
    public function delete(Request $request, Table $table): Response
    {
        if ($this->isCsrfTokenValid('delete'.$table->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($table);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_table_index');
    }
}
