<?php
/**
 * Hello World controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloWorldController.
 *
 * @Route("/hello-world")
 */
class HelloWorldController
{
    /**
     * Index action.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/"
     * )
     */
    public function index(): Response
    {
        $input = array("Neo", "Morpheus", "Trinity", "Cypher", "Tank");
        $numb=rand(0,4);
        $random=$input[$numb];
        return new Response('Hello '.$random.'!');
    }
}