<?php

namespace TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TaskBundle\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;




class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tasks = $em->getRepository('TaskBundle:Task')->findAll();
        
        return $this->render('TaskBundle:Default:index.html.twig',array(
          'tasks' => $tasks
        ));
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $task = $em->getRepository('TaskBundle:Task')->find($id);
        
        $em->remove($task);
        $em->flush();

        return $this->redirect($this->generateUrl('task_homepage'));
    }

    public function addAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        
        $task = new Task();

        $form = $this->createFormBuilder($task)
        ->add('name',TextType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Medium'=>'Medium','High'=>'High'),'attr'=>array('class'=>'col-md-4 form-control')))
        ->add('submit',SubmitType::class,array('attr'=>array('class'=>'col-md-4 form-control btn btn-primary')))
        ->getForm();

         $form->handleRequest($request);
         
         if($form->isSubmitted()){
            $task->setName($form['name']->getData());
            $task->setDescription($form['description']->getData());
            $task->setPriority($form['priority']->getData());
            $task->setCreatedDate(new \DateTime('now'));
            $task->setDueDate($form['due_date']->getData());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success','your task has been successfuly persisted !');

            return $this->redirect($this->generateUrl('task_homepage'));
         }

        return $this->render('TaskBundle:Default:create_task.html.twig',array(
            'form' => $form->createView()
        ));

    }

    public function updateAction(Request $request,$id){
        $em = $this->getDoctrine()->getEntityManager();
        
        $task = $em->getRepository('TaskBundle:Task')->find($id);

        $form = $this->createFormBuilder($task)
        ->add('name',TextType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'col-md-4 form-control')))
        ->add('priority',ChoiceType::class,array('choices'=>array('Low'=>'Low','Medium'=>'Medium','High'=>'High'),'attr'=>array('class'=>'col-md-4 form-control')))
        ->add('submit',SubmitType::class,array('attr'=>array('class'=>'col-md-4 form-control btn btn-primary')))
        ->getForm();

         $form->handleRequest($request);
         
         if($form->isSubmitted()){
            $task->setName($form['name']->getData());
            $task->setDescription($form['description']->getData());
            $task->setPriority($form['priority']->getData());
            $task->setCreatedDate(new \DateTime('now'));
            $task->setDueDate($form['due_date']->getData());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success','your task has been successfuly updated !');

            return $this->redirect($this->generateUrl('task_homepage'));
         }

        return $this->render('TaskBundle:Default:task_update.html.twig',array(
            'form' => $form->createView()
        ));
    }

}
