<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/Decouvertes", name="article.index")
     */
    public function index(ArticleRepository $articleRepository):Response
    {
        $results = $articleRepository->findAll();
        return $this->render('article/article.html.twig', [
            'results' => $results
        ]);
    }

    


    /**
     * @Route("Decouvertes/details/{id}", name="article.details")
     */
    public function details(ArticleRepository $articleRepository, int $id):Response
    {
        $result = $articleRepository->findOneBy([
			'id' => $id
		]);
        return $this->render('article/details.html.twig', [
            'result' => $result
        ]);
    }

    /**
	 * @Route("/article/form", name="article.form")
	 * @Route("/article/form/update/{id}", name="article.form.update")
	 */
	public function form(Request $request, EntityManagerInterface $entityManager, int $id = null, ArticleRepository $articleRepository):Response
	{
		// affichage d'un formulaire
		$type = ArticleType::class;
		$model = $id ? $articleRepository->find($id) : new Article();
		$form = $this->createForm($type, $model);
		$form->handleRequest($request);

		// si le formulaire est valide
		if($form->isSubmitted() && $form->isValid()){
			
			
			$id ? null : $entityManager->persist($model);
			$entityManager->flush();

			// message de confirmation
			$message = $id ? "L'article a été modifié" : "L'article a été ajouté";
			$this->addFlash('notice', $message);

			// redirection
			return $this->redirectToRoute('article.index');
		}

		return $this->render('article/form.html.twig', [
			'form' => $form->createView()
		]);
	}

    /**
	 * @Route("/Decouvertes/delete/{id}", name="article.delete")
	 */
	public function delete(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, int $id):Response
	{
		
		$entity = $articleRepository->find($id);
		$entityManager->remove($entity);
		$entityManager->flush();

		// message de confirmation et redirection
		$this->addFlash('notice', 'Le produit a été supprimé');
		return $this->redirectToRoute('article.index');
	}
}
