<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @extend AbstractController 
 * @__construct utilise le respository de Company,User et les Token StorageInterface,JWTTokenManagerInterface
 * à la construction de la class company Controller va recuperer le token Trouver son indentifant puis verifier dans la base de donner si il y a un user avec
 * cette identifiant et l'assigne à une entity User utilisable de manière privé pour cette classe (pas besoin de verification parce que si cette donné 
 * n'existerait pas à l'autentification j'aurai une erreur "bad crédential")
 */
#[AsController]
#[Route('/api/company', name: 'companies')]
class CompanyController extends AbstractController
{
    private $jwtToken;
    private $userJwtIndentifer;
    private User $user;
//---------------------------------------
    public function __construct(
        private EntityManagerInterface $manager,
        private CompanyRepository $company,  
        private UserRepository $userRepository,
        private TokenStorageInterface $tokenStorageInterface,
        private JWTTokenManagerInterface $jwtManager,
    ){
        $this->jwtToken = $this->tokenStorageInterface->getToken();
        $this->userJwtIndentifer = $this->jwtToken->getUser()->getUserIdentifier();
        $this->user = $userRepository?->findOneBy(['email'=> $this->userJwtIndentifer]);
    }
//---------------------------------------
    /**
     * @public method permettant de retourné toute les companies qui on l'user identifier par le token Jwt
     * @return JsonResponse 
     */
    #[Route(
    path:'/',
    name: '_get_all',
    methods:['GET'],
    defaults: [
        '_api_resource_class' => Company::class,
    ],
    )]
    public function index(): Response
    {
        $allCompanies = $this->company->findBy(['user'=>$this->user]);
        $companies = [];
        foreach($allCompanies as $company)
        {
            $companies[] = [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'siretNum' => $company->getSiretNum(),
            'adress'=> $company->getAdress(),
            'user'=>$company->getRoleUser(),
            ];
        }
        $result["result"] = $companies;

        return new JsonResponse($result);
    }
//---------------------------------------
    /**
     * @public method permettant de retourné la company qui à l'user identifier par le token Jwt et l'id spécifier dans le path la recherche est nullable donc 
     * si findOneBy n'est pas trouver le result sera null 
     * @return JsonResponse 
     */
    #[Route(path:'/{company_id}',
    name: '_get_one',
    methods:['GET'],
    defaults: [
        '_api_resource_class' => Company::class,
    ]
    )]
    public function getOneCompany(
        int $company_id
    ): Response
    { 
        $company = $this->company?->findOneBy(['id'=>$company_id,'user'=>$this->user]);
        $condition = ($company !== null);
        $companies[] =($condition)? [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'siretNum' => $company->getSiretNum(),
            'adress'=> $company->getAdress(),
            'user'=>$company->getRoleUser(),
        ]:null;

        $result["result"] = $companies;
        
        return new JsonResponse($result);
    }
//---------------------------------------
}
