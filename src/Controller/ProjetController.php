<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @class projet controller permettant d'effectuer du CRUD sur la base de données
 * @utilise l'injectable du respository projet ainsi que  EntityManagerInterface
 */
#[Route('/api/projet', name: 'projets')]
class ProjetController extends AbstractController
{
//---------------------------------------
    public function __construct(
        private EntityManagerInterface $manager,
        private ProjetRepository $projet,
    ){}
//---------------------------------------
    /**
     * @public method permettant de trouver tous les projets dont l'id de la company est specifier dans le path 
     * @return jsonResponse  
     */
    #[Route(path:'/{company_id}',
    methods:['GET'],
    name:'_get_all',
    defaults: [
        '_api_resource_class' => Projet::class,
    ],
    )]
    public function index(
        #[MapEntity(id:'company_id')]
        ?Company $company
    ): Response
    {
        if(!is_null($company))
        {
            $ObjectProjetCompany = $this->projet?->findBy(['company'=>$company]);
            $arrayprojetCompany = [];
            foreach($ObjectProjetCompany as $projet)
            {
                $arrayprojetCompany[] = [
                    'id' => $projet->getId(),
                    'title' => $projet->getTitle(),
                    'Description' => $projet->getDescription(),
                    'creation_Date'=> $projet->getCreationDate(),
                    'Updated_Date'=> $projet->getUpdatedAt(),
                ];
            }
            $result["result"] =  $arrayprojetCompany;
        } else {
            $result["result"] = null;
        }
        return new JsonResponse($result);
    }
//---------------------------------------
    /**
     * @public method permettant de trouver tous le projet dont l'id de la company et son propre id est specifier dans le path 
     * @return jsonResponse  
     */
    #[Route(path:'/{company_id}/{id}',
    methods:['GET'],
    name: '_get_one',
    defaults: [
        '_api_resource_class' => Projet::class,
    ]
    )]
    public function projetOne(
        #[MapEntity(id:'company_id')]
        ?Company $company,
        int $id,
        ): Response
    {   
        $projet = $this->projet?->findOneBy(
            ["id"=>$id ,"company"=>$company ]
        );
        $condition = ($projet !== null);
        $projet_array[] =($condition)? [
            'id' => $projet->getId(),
            'title' => $projet->getTitle(),
            'Description' => $projet->getDescription(),
            'creation_Date'=> $projet->getCreationDate(),
            'Updated_Date'=> $projet->getUpdatedAt(),
        ]:null;

        $result["result"] = $projet_array;
        return new JsonResponse($result);
    }
//---------------------------------------
    /**
     * @public method permettant de crée un projet en fonction d'un body json des valeurs par défaut son donné pour le titre et et la discription si cela
     * n'est pas fait 
     * @return jsonResponse  
     */
    #[Route(path:'/{company_id}',
    methods: ['POST'],
    name: '_create',
    defaults: [
        '_api_resource_class' => Projet::class,
    ]
    )]
    public function create_projet(
        #[MapEntity(id:'company_id')]
        ?Company $company,
        Request $request
    ):Response
    {
        $content = $request?->getContent();
        $condition = ($content !== null);
        $data =($condition)? json_decode($content, true) : null;
        if($data !== null)
        {
            $data['title'] ??= 'default_title';
            $data['description'] ??= "default_Description";
            $projet = $this->projet->findOneBy(
                [
                    "title" =>  $data["title"],
                    "description"=>$data['description'],
                    "company" => $company,
                ]
            );
            if(!is_null($projet))
            {
                return new JsonResponse([
                    "result" => 'projet already exist',
                ]);
            } else {
                $projet = new Projet();
                $date = new \DateTimeImmutable();// la valeur par défaut est now 
                $projet->setCompany($company)
                ->setCreationDate($date)
                ->setUpdatedAt($date)
                ->setTitle($data['title'])
                ->setDescription($data['description']);
                $this->manager->persist($projet);
                $this->manager->flush();
                $data = [
                    "status" => 200,
                    "result" => "new projet as been save",
                ];
            }
        }
        return new JsonResponse(
            [
                'result'=> $data
            ]
        );
    }
//---------------------------------------
    /**
     * @public method permettant de modifier un projet en fonction d'un body json des valeurs par défaut son donné pour le titre et et la discription si cela
     * n'est pas fait,la date updatedat est automatiquement changer à maintenant,la modification est autorisé en fonction du role donné par la sociète à l'utilisateur 
     * @return jsonResponse  
     */
    #[Route('/{company_id}/{id}',
    methods:['Patch'],
    name: '_patch',
    defaults: [
        '_api_resource_class' => Projet::class,
    ]
    )]
    public function ModifProjet(
        #[MapEntity(id:'id')]
        ?Projet $projet,
        #[MapEntity(id:'company_id')]
        ?Company $company,
        Request $request
        ): Response
    {
        if(is_null($projet)){
            return new JsonResponse(
                ["result" => "No result find for this id"]
            );
        } else {
            if($company->getRoleUser() != 'CONSULTANT')
            {
                $content = $request?->getContent();
                $condition = ($content !== null);
                $data = ($condition)? json_decode($content, true) : null;
                $data['description'] ??= "default_Description";
                $data['title'] ??= "default_title";
                $projet->setTitle($data['title'])
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setDescription($data['description'])
                ;
                $this->manager->persist($projet);
                $this->manager->flush();
                return new JsonResponse(
                    ["result" => "the project as been updated"]
                );
            } else {
                return new JsonResponse(
                    ["result" => "You didn't have the credential for this action"]
                );
            }
        }
    }
//---------------------------------------
    /**
     * @public method permettant de supprimer un projet en fonction du role donné par sa sociète  
     * @return jsonResponse  
     */
    #[Route(path:'/{company_id}/{id}',
    methods:['DELETE'],
    name: '_delete',
    defaults: [
        '_api_resource_class' => Projet::class,
    ]
    )]
    public function DeleteProjet(
        #[MapEntity(id:'id')]
        ?Projet $projet,
        #[MapEntity(id:'company_id')]
        ?Company $company,
    ): Response
    {
        if(is_null($projet)||is_null($company)){
            return new JsonResponse(
                ["result" => "No result find for this id"]
            );
        } else {
            if($company->getRoleUser() == 'ADMIN')
            {
                $this->manager->remove($projet);
                $this->manager->flush();
                return new JsonResponse(
                    ["result" => "the project as been remove"]
                );
            } else {
                return new JsonResponse(
                    ["result" => "You didn't have the credential for this action"]
                );
            }
        }
    }
//---------------------------------------
}
