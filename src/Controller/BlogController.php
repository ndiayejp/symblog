<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index( PostRepository $repo)
    {
 
        $posts = $repo->findAll();
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'posts' =>$posts
        ]);
    }

    /**
    * @Route("/", name="home")
    */

    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/create",name="blog_create")
     * @Route("/blog/{id}/edit",name="blog_edit")
     */
    public function form(Post $post = null, Request $request,ObjectManager $manager){

        if(!$post){
            $post = new Post();
        }
       

        $form = $this->createForm(PostType::class,$post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$post->getId()){
                $post->setCreatedAt(new \Datetime());
            }
            
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post_show',['id'=>$post->getId()]);
        }


        return $this->render('blog/create.html.twig',[
            'formPost'=>$form->createView(),
            'editMode'=> $post->getID() !=null
        ]);
    }

    /**
     * @Route("/show/{id}", name="post_show")
     */

    public function show(Post $post){


 
        return $this->render('blog/show.html.twig',[
            'post'=>$post
        ]);
    }

  
}
