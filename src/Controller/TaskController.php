<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    /**
     * @Route("/task", name= "task")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/task/new", name= "task_new")
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $tag1 = new Tag();

        $tag1->setName('takes 1 hour');
        $task->addTag($tag1);
        $tag2 = new Tag();
        $tag2->setName('complicated');
        $task->addTag($tag2);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/task/edit/{task}", name="task_edit")
     */
    public function edit(Task $task, Request $request){

        $form = $this->createForm(TaskType::class, $task);

        /**
         * @var Collection|Tag[]
         */
        $originalTags = new ArrayCollection();

        foreach ($task->getTags() as $tag){
            $originalTags->add($tag);
        }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();


            foreach ($originalTags as $originalTag){
                if(!$task->getTags()->contains($originalTag)){
                    $originalTag->setTask(null);

                }
            }

            $em->persist($task);
            $em->flush();
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
