<?php

namespace App\Controller;


 use App\Entity\PostLike;
 use App\Repository\PostRepository;

use App\Entity\Post;

use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(PostRepository $repo)
    {
        return $this->render('post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    
    }

    /**
     * permet de liker ou unliker un article
     * 
     * @Route("/post/{id}/like", name="post_like")
     
     
     */
    public function like(Post $post, EntityManagerInterface $manager,PostLikeRepository $likeRepo ) :  Response 
   
    {
        return $this->json([ 'code' => 200, 
        'message' => 'Votre avez bien liké cet article',
        'like' => $likeRepo->count(['post' => $post])
    
    
    ], 200);



        
    }
}
