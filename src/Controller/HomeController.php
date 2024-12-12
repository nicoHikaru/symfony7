<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        /* 
            INSERT INTO `user`(`email`, `password`, `score`, `name`) select 'coucou2@gmail.com',user.password,user.score,user.name from `user` where id = 1;
            Entrainnement
            SELECT user.id,user.name,user.email,user.score,city.name as city_name,city.population,departement.name as departement_name,dense_rank() over (ORDER BY user.score desc) as score_rang from user 
            INNER JOIN user_live on user_live.id_user = user.id 
            INNER JOIN city on city.id = user_live.city_id 
            INNER JOIN city_departement on city_departement.city_id = city.id 
            INNER JOIN departement on departement.id = city_departement.departement_id where user.email like "%@gmail%" and departement.name = "var" and city.name like "saint%"
        
        */
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
