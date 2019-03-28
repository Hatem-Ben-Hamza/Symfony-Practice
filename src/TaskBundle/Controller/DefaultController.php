<?php

namespace TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
