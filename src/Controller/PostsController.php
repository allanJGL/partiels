<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/newPost/{id}", name="newPost")
     */
    public function newPost(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $post = new Posts();
        $user = $entityManager->getRepository(User::class)->find($id);
        $post->setUser($user);
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('posts/savePost.html.twig', [
            'postForm' => $form->createView(),
            'controller_name' => 'PostsController'
        ]);

    }
}
