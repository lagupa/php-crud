<?php
namespace App\Controller;

use App\Entity\Blog;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BlogController extends AbstractController
{
    // LIST BLOG

    /**
    * @Route("/blogs/", name="blog_list")
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

    // BLOG DETAILS

    /**
    * @Route("/blogs/{id}", name="blog_detail")
    * @Method({"GET"})
    */
    public function readBlogsDetails(ManagerRegistry $doctrine, int $id): Response
    {
        $blog = $doctrine->getRepository(Blog::class)->find($id);
        return $this->render(
            'blogs/read-blog-detail.html.twig',
            ["blog"=>$blog]
        );
    }
}