<?php
namespace App\Controller;

use App\Entity\Blog;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class BlogController extends AbstractController
{
    // LISTING BLOG
    /**
    * @Route("/blogs/", name="list_blog")
    * @Method({"GET"})
    */
    public function readBlogs(ManagerRegistry $doctrine): Response
    {
        $blogs = $doctrine->getRepository(Blog::class)->findAll();
        return $this->render(
            'blogs/read-blog.html.twig',
            ["blogs"=>$blogs]
        );
    }


    // CREATING BLOG
    /**
    * @Route("/blogs/create", name="create_blog")
    * @Method({"GET", "POST"})
    */
    public function createBlog(Request $request, ManagerRegistry $doctrine): Response
    {
        $blog = new Blog();

        $form = $this->createFormBuilder($blog)
        ->add('title', TextType::class,
           array('attr' => array('class' => "form-control")))
        ->add('content', TextareaType::class, array(
            'required' => False, 'attr' => array('class' => 'btn btn-primary')))
        ->add('save', SubmitType::class, array('label' => 'Create','attr' => array('class' => 'btn btn-primary')))
        ->getForm();

        // Process Form Data
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newBlog = $form->getData();

            // save form data into database;
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newBlog);
            $entityManager->flush();
            return $this->redirectToRoute('list_blog');
        }


        return $this->render(
            'blogs/create-blog.html.twig',
            ["form"=>$form->createView()]
        );
    }


    // DETAILING BLOG
    /**
    * @Route("/blogs/detail/{id}", name="detail_blog")
    * @Method({"GET"})
    */
    public function readBlogsDetails(ManagerRegistry $doctrine, $id): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->find($id);
        return $this->render(
            'blogs/read-blog-detail.html.twig',
            ["blog"=>$blog]
        );
    }


    // DELETING BLOG
    /**
    * @Route("/blogs/delete/{id}", name="delete_blog")
    * @Method({"DELETE"})
    */
    public function deleteBlog(ManagerRegistry $doctrine, $id): Response
    {
        // find blog to be deleted
        $blog = $doctrine->getRepository(Blog::class)->find($id);

        // create entity manager
        $entityManager = $doctrine->getManager();

        // delete blog
        $entityManager->remove($blog);

        // refresh db
        $entityManager->flush();

        return $this->redirectToRoute('list_blog');
    }

    // EDITING BLOG
    /**
    * @Route("/blogs/edit/{id}", name="edit_blog")
    * @Method({"GET", "POST"})
    */
    public function editBlog(Request $request, ManagerRegistry $doctrine, $id): Response
    {

        // find blog to be edited
        $blog = $doctrine->getRepository(Blog::class)->find($id);

        // create entity manager
        $entityManager = $doctrine->getManager();

        $form = $this->createFormBuilder($blog)
        ->add('title', TextType::class,
           array('attr' => array('class' => "form-control")))
        ->add('content', TextareaType::class, array(
            'required' => False, 'attr' => array('class' => 'btn btn-primary')))
        ->add('save', SubmitType::class, array('label' => 'Save','attr' => array('class' => 'btn btn-primary')))
        ->getForm();

        // Process Form Data
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // save form data into database;
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('list_blog');
        }

        return $this->render(
            'blogs/edit-blog.html.twig',
            ["form"=>$form->createView()]
        );
    }
}