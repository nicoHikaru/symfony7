<?php

namespace App\Controller;

use App\Entity\Files;
use App\Form\UploadType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class FileController extends AbstractController
{
    #[Route('/file', name: 'app_file')]
    public function index(Request $request, SluggerInterface $slugger, ?string $brochuresDirectory = null): Response
    {
        $files = new Files();
        $form = $this->createForm(UploadType::class, $files,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                //$files->setBrochureFilename($newFilename);
                $files->setBrochureFilename(
                    new File($brochuresDirectory.DIRECTORY_SEPARATOR.$files->getBrochureFilename())
                );
                
            }

            // ... persist the $product variable or any other work

            return $this->redirectToRoute('app_file');
        }
        
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
            'form' => $form
        ]);
    }
}
