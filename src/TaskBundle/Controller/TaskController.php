<?php

namespace TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use TaskBundle\Entity\Task;
use TaskBundle\Entity\User;
use TaskBundle\Entity\Priority;
use TaskBundle\Entity\Status;

/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller
{
    
    /**
     * Lists all task entities.
     *
     * @Route("/", name="task_index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('TaskBundle:Task');
        
        //wyszukuję zalogowanego użytkownika
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
        
        $searchForm = $this->createForm('TaskBundle\Form\SearchTaskType');
        
        $searchForm->handleRequest($request);
        
        if($searchForm->isSubmitted()){
            
            $name = $searchForm["name"]->getData();
            $desc = $searchForm["desc"]->getData();
            $dateFrom = $searchForm["dateFrom"]->getData();
            $dateTo = $searchForm["dateTo"]->getData();
            $deadlineFrom = $searchForm["deadlineFrom"]->getData();
            $deadlineTo = $searchForm["deadlineTo"]->getData();
            $priority = $searchForm["priority"]->getData();
            $category = $searchForm["category"]->getData();
            $status = $searchForm["status"]->getData();
            
            $tasks = $repo->findTasks($user->getId(), $name, $desc, $dateFrom, $dateTo, $deadlineFrom, $deadlineTo, $priority, $category, $status);
          
            return $this->render('task/index.html.twig', array(
                'tasks' => $tasks,
                'user' => $user->getUserName(),
                'searchForm' => $searchForm->createView()
            ));
        }
        
        
        
        if($user instanceof User) {
            $tasks = $repo->findTaskByUser($user->getId());
        } else {
            $tasks = [];
        }
        
        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
            'user' => $user->getUsername(),
            'searchForm' => $searchForm->createView()
        ));
    }
    
    /**
     * Lists all task by actuall date.
     *
     * @Route("/byActualDay", name="task_day")
     * @Method("GET")
     */
    public function tasksForActualDayAction()
    {

        $repo = $this->getDoctrine()->getRepository('TaskBundle:Task');
        
        //wyszukuję zalogowanego użytkownika
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
        
        if($user instanceof User) {
            $tasks = $repo->findTaskByUserAndDay($user->getId());
        } else {
            $tasks = [];
        }
        
        return $this->render('task/task_by_day.html.twig', array(
            'tasks' => $tasks,
            'user' => $user->getUsername()
        ));
    }
     
    
    /**
     * Creates a new task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm('TaskBundle\Form\TaskType', $task);
        $form->handleRequest($request);
        
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            $em = $this->getDoctrine()->getManager();
            $date = new \DateTime();
            $task->setDate($date);
            $file = $task->getAttach();
            $fileName = $user->getId().'_'.date('Y-m-d').'.'.$file->guessExtension();//znajduje rozszerzenie dołączanego pliku
            $file->move($this->getParameter('uploadedFiles'), $fileName);
            $task->setAttach($fileName);
            $task->setUser($user);
            $em->persist($task);
            $em->flush($task);

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('task/new.html.twig', array(
            'task' => $task,
            'user' => $user->getUsername(),
            'form' => $form->createView(),
        ));
    }
    public function checkTaskUser(Task $task){
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
                
        if($task->getUser() != $user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access denied!');
        }
    }
    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {
        $this->checkTaskUser($task);//user moze przeglądac tylko swoje taski
        $deleteForm = $this->createDeleteForm($task);
        
        $user = $this->container //wczytuję usera na potrzebę przekazania do wyświetlenia w twigu edit
                ->get('security.context')
                ->getToken()
                ->getUser();
        
        $commentRepo = $this->getDoctrine()->getRepository('TaskBundle:Comment');
        $commentToTask = $commentRepo->findCommentsByTaskId($task->getId());

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'user' => $user->getUsername(),
            'comments' => $commentToTask,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task, $id)
    {
        $this->checkTaskUser($task);//tylko user danego tasku może edytować task
        
        $taskRepo = $this->getDoctrine()->getRepository('TaskBundle:Task');
        $tasktoEdit = $taskRepo->find($id);
        
        $user = $this->container //wczytuję usera na potrzebę przekazania do wyświetlenia w twigu edit
                ->get('security.context')
                ->getToken()
                ->getUser();
        
        if($tasktoEdit->getStatus()->getName() != 'completed'){
        
            $deleteForm = $this->createDeleteForm($task);
            $editForm = $this->createForm('TaskBundle\Form\TaskType', $task);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('task_show', array('id' => $task->getId()));
            }
            
            return $this->render('task/edit.html.twig', array(
                'task' => $task,
                'user' => $user->getUsername(),
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        } else {
            
            $commentRepo = $this->getDoctrine()->getRepository('TaskBundle:Comment');
            $commentToTask = $commentRepo->findCommentsByTaskId($task->getId());
            
            $deleteForm = $this->createDeleteForm($task);
            
            return $this->render('task/show.html.twig', array(
            'task' => $task,
            'user' => $user->getUsername(),
            'comments' => $commentToTask,
            'delete_form' => $deleteForm->createView(),
            'message' => "You can't edit completed tasks!",
            ));
        }
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $this->checkTaskUser($task);//zabezpieczenie(tylko user danego tasku moze usuwac task
        
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush($task);
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
