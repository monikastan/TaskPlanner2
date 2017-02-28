<?php

namespace TaskBundle\Controller;

use TaskBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use TaskBundle\Entity\User;


/**
 * Comment controller.
 *
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('TaskBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new comment entity.
     *
     * @Route("/new/{id}", name="comment_new", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id){
        $comment = new Comment();
        $form = $this->createForm('TaskBundle\Form\CommentType', $comment);
        $form->handleRequest($request);
        
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
        
        $taskRepo = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $task = $taskRepo->find($id);

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User && $task != null) {
            $em = $this->getDoctrine()->getManager();
            $comment->setUser($user);
            $comment->setTask($task);
            
            $date = new \DateTime();
            $comment->setDate($date);
            
            $em->persist($comment);
            $em->flush($comment);

            //return $this->redirectToRoute('comment_show', array('id' => $comment->getId()));
            return $this->redirectToRoute('task_show', ['id' => $id]);
        }
        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'taskId' => $id,
        ));
    }

    /**
     * Finds and displays a comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     */
    public function showAction(Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);
        $editForm = $this->createForm('TaskBundle\Form\CommentType', $comment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_edit', array('id' => $comment->getId()));
        }

        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteForm($comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush($comment);
        }

        return $this->redirectToRoute('comment_index');
    }

    /**
     * Creates a form to delete a comment entity.
     *
     * @param Comment $comment The comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
