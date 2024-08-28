<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\AddFormType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use App\Service\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/wishes', name: 'wishes_')]
class WishController extends AbstractController
{

    #[Route('/', name: 'list')]
    public function showWishes(WishRepository $wishRep)
    {
        return $this->render('wish/index.html.twig', [
            'title' => 'Wishes',
            'wishes' => $wishRep->findBy(['isPublished' => true], ["dateCreated" => "DESC"])
        ]);
    }

    #[Route('/{id<\d+>}', name: 'detail')]
    public function showDetail(Wish $wish, Censurator $censurator): Response
    {
        $wish->setDescription($censurator->purify($wish->getDescription()));
//
//        if (!$wish) {
//            throw $this->createNotFoundException('Le souhait demandé n\'existe pas.');
//        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: 'delete', methods: ['POST'])]
    public function delete(Wish $wish, EntityManagerInterface $entityManager): RedirectResponse
    {
        $wish->updatePublished(false);
        $entityManager->flush();

        return $this->redirectToRoute('wishes_list');
    }

    #[Route('/edit/{id<\d+>}', name: 'edit')]
    public function edit(
        Wish $wish,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/png')] string $uploadImageDirectory
    ): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $wish->setAuthor($user->getUsername());
        }
        $form = $this->createForm(AddFormType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadImageFile = $form->get('image')->getData();
            if ($uploadImageFile) {
                $originalFilename = pathinfo($uploadImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadImageFile->guessExtension();

                try {
                    $uploadImageFile->move($uploadImageDirectory, $newFilename);
                } catch (FileException $e) {
                    throw $this->createNotFoundException("N'est pas conforme au format attendu");
                }

                $wish->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('wishes_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/addForm.html.twig', [
            'title' => 'Update your wishes!',
            'wishForm' => $form->createView(),
            'wish' => $wish,
        ]);
    }

    #[Route('/addForm}', name: 'addForm', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function wishAddForm(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/png')] string $uploadImageDirectory,
        CategoryRepository $categoryRepo
    ): Response
    {
        $wish = new Wish();
        $user = $this->getUser();
        $wish->setAuthor($user->getUsername());
        $form = $this->createForm(AddFormType::class, $wish);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadImageFile = $form->get('image')->getData();
            if ($uploadImageFile) {
                $originalFilename = pathinfo($uploadImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadImageFile->guessExtension();

                try {
                    $uploadImageFile->move($uploadImageDirectory, $newFilename);
                } catch (FileException $e) {
                    throw $this->createNotFoundException("N'est pas conforme au format attendu");
                }

                $wish->setImage($newFilename);
            }

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Idea successfully added');
            return $this->redirectToRoute('wishes_list');
        }

        return $this->render('wish/addForm.html.twig', [
            'title' => 'Add your wish!',
            'wishForm' => $form,
        ]);
    }
}
