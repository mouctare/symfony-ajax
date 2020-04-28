<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\PostLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class AppFixtures extends Fixture
{

     /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        $user = []; // vue qu'on a plusieurs utilisateurs on les mets dans un tableau

        $user = new User();
        $user->setEmail('user@symfony.com')
            ->setPassword($this->encoder->encodePassword($user, 'password'));

        $manager->persist($user);
        $users[] = $user;



        for ($i = 0; $i < 20; $i++){
             $user = new User(); // on crée un utilisateur à, chaque fois qu'on passe dans cette boucle.
            $user->setEmail($faker->email)
            ->setPassword($this->encoder->encodePassword($user, 'password'));
               

            $manager->persist($user);
            $users[] = $user;



        }



        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6))
                ->setIntroduction($faker->paragraph())
                ->setContent('<p>' . join(',', $faker->paragraphs()) . '</p>');

            $manager->persist($post);

            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $like = new PostLike();
                $like->setPost($post)
                    ->setUser($faker->randomElement($users));

                    $manager->persist($like);
            }
        }

        $manager->flush();

    
    }
}
