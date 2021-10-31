<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\External\CSVExternal;
use App\External\MergeExternal;
use App\Form\CSVType;
use Symfony\Component\Form\FormTypeInterface;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/merge', name: "admin_merge", methods: "GET")]
    public function merge(Request $request): Response
    {
        $mergeForm = $this->createForm(CSVType::class);
        $mergeForm->handleRequest($request);

        if ($mergeForm->isSubmitted() && $mergeForm->isValid())
        {
            $file0 = $mergeForm['file0']->getData();
            $file1 = $mergeForm['file1']->getData();

            if(!CSVExternal::isValidFileHeader(CSVExternal::getFileHeader($file0)))
            {
                $this->addFlash("error", "Le fichier ne contient pas toutes les colonnes nécessaires pour cette action !");
                $this->redirectToRoute("merge");
            }

            if(!CSVExternal::isValidFileHeader(CSVExternal::getFileHeader($file1)))
            {
                $this->addFlash("error", "Le fichier ne contient pas toutes les colonnes nécessaires pour cette action !");
                $this->redirectToRoute("merge");
            }

            $merge = new MergeExternal($file0, $file1);

            if($mergeForm["choice"]->getData() == "Sequentiel")
            {
                $mergeS = $merge->sequential();

                if($mergeS)
                {
                    $merge->ddl();
                } else {
                    $this->addFlash("error", "La fusion n'a pas pus aboutir !");
                    $this->redirectToRoute("merge");
                }
            }
            return new Response();
        }
        return $this->render('admin/merge.html.twig', [
            "mergeForm" => $mergeForm->createView()
        ]);
    }
}
