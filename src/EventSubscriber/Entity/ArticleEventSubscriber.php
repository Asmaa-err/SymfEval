<?php

namespace App\EventSubscriber\Entity;



use App\Entity\Article;
use App\Service\FileService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleEventSubscriber implements EventSubscriber
{
	
	private $slugger;
	private $fileService;

	public function __construct(SluggerInterface $slugger, FileService $fileService)
	{
		$this->slugger = $slugger;
		$this->fileService = $fileService;
	}

	public function prePersist(LifecycleEventArgs $event):void
	{
		// par défaut, les souscripteurs doctrine écoutent toutes les entités
		if($event->getObject() instanceof Article){
			$article = $event->getObject();

			// création du slug
			//$article->setSlug( $this->slugger->slug($article->getName())->lower() );

			// transfert de l'image
			if($article->getImage() instanceof UploadedFile){
				// appel d'un service
				$this->fileService->upload( $article->getImage(), 'img/' );

				// récupération du nom aléatoire du fichier généré dans le service
				$article->setImage( $this->fileService->getFileName() );
			}
		}
	}

	
	public function getSubscribedEvents()
	{
		return [
			Events::prePersist,
			Events::postLoad,
			Events::preUpdate,
			//Events::preRemove
		];
	}

	

	public function preUpdate(LifecycleEventArgs $args):void
	{
		if($args->getObject() instanceof Article){
			$article = $args->getObject();
			// gestion de l'image
			// si une nouvelle image a été sélectionnée
			if($article->getImage() instanceof UploadedFile){
				// transfert de la nouvelle image
				$this->fileService->upload($article->getImage(), 'img/');
				$article->setImage( $this->fileService->getFileName() );

				// supprimer l'ancienne image à partir de la propriété dynamique créée dans l'événement postLoad
				$this->fileService->delete( $article->prevImage, 'img/' );
			}
			// si aucune image n'a été sélectionnée
			else {
				// récupération de la propriété dynamique créée dans l'événement postLoad
				$article->setImage( $article->prevImage );
			}
		}
	}

	public function postLoad(LifecycleEventArgs $args):void
	{
		if($args->getObject() instanceof Article){
			// création d'une propriété dynamique permettant de stocker le nom de l'image
			$article = $args->getObject();
			$article->prevImage = $article->getImage();
			
		}
	}
}
