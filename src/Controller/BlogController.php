<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
    * @Route("/blogs/")
    * @Method({"GET"})
    */
    public function readBlogs(): Response
    {
         $blogs = [
            (object) [
                "id" => 1,
                'title' => 'Blog 1',
                'content' => 'This is blog content 1'
            ],
            (object) [
                "id" => 2,
                'title' => 'Blog 2',
                'content' => 'This is blog content 2'
            ],
        ];

        return $this->render(
            'blogs/read-blog.html.twig',
            ["blogs"=>$blogs]
        );
    }
}