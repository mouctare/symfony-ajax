<?php

namespace App\Controller;


 use App\Entity\PostLike;
 use App\Repository\PostRepository;

use App\Entity\Post;

use App\Entity\User;
use App\Repository\UserRepository;

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
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403 ,
            'message' => "Unauthorized"
        ], 403);

        if($post->isLikedByUser($user)) {
            // sil user a déjà liker , donc on va essaye de le trouver
            $like = $likeRepo->findOneBy([
                'post' => $post,
                'user' => $user

            ]);
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['post' => $post])


            ], 200);

        }

        $like = new PostLike();
        $like->setPost($post)
            ->setUser($user);
            $manager->persist($like);
            $manager->flush();

        return $this->json([ 'code' => 200, 
        'message' => 'Like bien ajouté',
        'like' => $likeRepo->count(['post' => $post])
    
    
    ], 200);



        
    }
}
