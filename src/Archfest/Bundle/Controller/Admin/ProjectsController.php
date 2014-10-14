<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleksandr
 * Date: 11/4/13
 * Time: 10:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Archfest\Bundle\Controller\Admin;

use Archfest\Bundle\Controller\Admin\BaseController as Controller;
use Archfest\Bundle\Entity\Founders;
use Archfest\Bundle\Entity\Projects;
use Archfest\Bundle\Entity\TypesOfFounders;
use Archfest\Bundle\Entity\TypesOfProjects;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller{
    /**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if(array_key_exists('object', $parameters)) {
            /** @var Projects $object */
            $object = $parameters['object'];
            $parameters['objectInfo'] = $this->getObjectInfo($object);
            $parameters['flashInfo'] = array();
            if($object->getFlash()) {
                $parameters['flashInfo'] = $this->getObjectFlashInfo($object);
            }
            $parameters['flashInfo'] = json_encode($parameters['flashInfo']);
        }
        return parent::render($view, $parameters, $response);
    }


    public function installAction() {
        $em = $this->getDoctrine()->getManager();
        $projectsTypeRepository = $em->getRepository('ArchfestBundle:TypesOfProjects');
        $projectsTypes = $projectsTypeRepository->findAll();

        /** @var TypesOfProjects $projectType */
        foreach($projectsTypes as $projectType) {
            $projects = $projectType->getProjects();
            $i = 1;
            /** @var Projects $project */
            foreach($projects as $project) {
                $project->setPosition($i);
                $i++;
            }
        }


        $foundersTypeRepository = $em->getRepository('ArchfestBundle:TypesOfFounders');
        $foundersTypes = $foundersTypeRepository->findAll();

        /** @var TypesOfFounders $founderType */
        foreach($foundersTypes as $founderType) {
            $founders = $founderType->getFounders();
            $i = 1;
            /** @var Founders $founder */
            foreach($founders as $founder) {
                $founder->setPosition($i);
                $i++;
            }
        }

        $em->flush();
        var_dump('bingo');
        die;
    }
}