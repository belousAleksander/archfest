<?php

namespace Archfest\Bundle\Controller;

use Archfest\Bundle\Entity\TypesOfProjects;
use Archfest\Bundle\Repository\ProjectImgRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $projectsRepository = $this->getDoctrine()->getRepository('ArchfestBundle:Projects');
        $projects = $projectsRepository->findBy(array(), array('id' => 'DESC'));
        $totalArea = 0;

        foreach($projects as $project)
        {
            $totalArea = $totalArea + $project->getArea();
        }

        $parameters['totalArea'] = $totalArea;

        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

    public function indexAction()
    {
        $locale = $this->getRequest()->get('_locale');
        $projectsRepository = $this->getDoctrine()->getRepository('ArchfestBundle:Projects');
        $projects = $projectsRepository->findBy(array(), array('id' => 'DESC'));
        $projects = array_slice($projects, 0, 15);
        $mainPageRepository = $this->getDoctrine()->getRepository('ArchfestBundle:MainPage');
        $object = $mainPageRepository->find(1);
        $object->setDefaultLocale($locale);
        return $this->render('ArchfestBundle:Frontend\index:index.html.twig', array('page' => $object, 'projects' => $projects));
    }

    public function aboutUsAction()
    {
        $typeOfFoundersRepository = $this->getDoctrine()->getRepository('ArchfestBundle:TypesOfFounders');
        $aboutUsRepository = $this->getDoctrine()->getRepository('ArchfestBundle:AboutUs');
        $aboutUsAll = $aboutUsRepository->findAll();
        $aboutUs = $aboutUsAll[0];
        $typesOfFounders = $typeOfFoundersRepository->findAll();
        $locale = $this->getRequest()->get('_locale');
        $aboutUs->setDefaultLocale($locale);
        return $this->render('ArchfestBundle:Frontend\aboutUs:aboutUs.html.twig', array(
            'page' => $aboutUs,
            'typesOfFounders' => $typesOfFounders
        ));
    }

    public function constructionAction()
    {
        $constructionRepository = $this->getDoctrine()->getRepository('ArchfestBundle:Construction');
        $constructions = $constructionRepository->findAll();

        $constructionPageRepository = $this->getDoctrine()->getRepository('ArchfestBundle:ConstructionPage');
        $constructionPage = $constructionPageRepository->findAll();
        $constructionPage = $constructionPage[0];
        $locale = $this->getRequest()->get('_locale');
        $constructionPage->setDefaultLocale($locale);
        return $this->render('ArchfestBundle:Frontend\construction:construction.html.twig', array(
            'page' => $constructionPage,
            'constructions' => $constructions
        ));
    }

    public function projectsAction()
    {
        $locale = $this->getRequest()->get('_locale');
        $mainPageRepository = $this->getDoctrine()->getRepository('ArchfestBundle:CatalogPage');
        $projectsTypesRepository = $this->getDoctrine()->getRepository('ArchfestBundle:TypesOfProjects');
        /** @var TypesOfProjects $projectsTypes */
        $projectsTypes = $projectsTypesRepository->findAll();
        $object = $mainPageRepository->findAll();
        $object = $object[0];
        $object->setDefaultLocale($locale);
        return $this->render('ArchfestBundle:Frontend\catalog:catalog.html.twig', array(
            'page' => $object,
            'projectsTypes' => $projectsTypes));
    }

    public function contactsAction()
    {
        $pageInfoRepository = $this->getDoctrine()->getRepository('ArchfestBundle:ContactUsPage');
        $pageInfo = $pageInfoRepository->findAll();

        $addressesRepository = $this->getDoctrine()->getRepository('ArchfestBundle:ContactUs');
        $addresses = $addressesRepository->findAll();
        $locale = $this->getRequest()->get('_locale');
        $pageInfo = $pageInfo[0];
        $pageInfo->setDefaultLocale($locale);

        if (count($pageInfo) === 0) {
            throw new Exception(sprintf('unable to find the object with'));
        }

        return $this->render('ArchfestBundle:Frontend\contacts:contacts.html.twig', array(
            'page' => $pageInfo,
            'addresses' => $addresses));
    }

    public function projectViewAction ($id) {
        $projectsRepository = $this->getDoctrine()->getRepository('ArchfestBundle:Projects');
        /** @var ProjectImgRepository $projectImgRepository */
        $projectImgRepository = $this->getDoctrine()->getRepository('ArchfestBundle:ProjectImg');
        $project = $projectsRepository->find($id);
        if (!$project || !$project->getEnabled()) {
            throw new Exception(sprintf('unable to find the object with id : %s', $id));
        }

        $projectMainImg = $projectImgRepository->getMainImage($id);
        $imgWithOutMain = $projectImgRepository->getImageWithOutMain($id);
        $locale = $this->getRequest()->get('_locale');
        $project->setDefaultLocale($locale);
        return $this->render('ArchfestBundle:Frontend\project:project.html.twig', array(
            'page' => $project,
            'mainImg' => $projectMainImg,
            'imgWithOutMain' => $imgWithOutMain
        ));
    }

    public function biographyAction(Request $request) {
        $id = $request->get('id');

        /** @var EntityRepository $projectImgRepository */
        $biographyRepository = $this->getDoctrine()->getRepository('ArchfestBundle:Founders');
        $biography = $biographyRepository->find($id);
        if (count($biography) == 0 && $biography->getStatus()) {
            throw new Exception(sprintf('unable to find the object with id : %s', $id));
        }

        return $this->render('ArchfestBundle:Frontend\founders:index.html.twig', array(
            'object' => $biography,
        ));
    }

    public function sendLetterAction (Request $request) {
        $response = array('success' => true);
        $name = $request->get('name');

        $from = $request->get('from');

        if (!preg_match('/^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,4}$/i',$from))
        {
            $response['success'] = false;
            $response['message'] = 'Please enter a valid E-mail address.';
            return new JsonResponse($response);
        }


        //Проверяем полученные из формы данные
        $subj ="Сообщение с archfest.com";
        $textName = 'Имя - '.$name.'<br>';
        $letterText = $request->get('text');
        $text = $textName.$letterText;
        $to = ("a_lexis@ua.fm");

        $un        = strtoupper(uniqid(time()));

        $head      = "From: $from\n";
        $head     .= "Subject: $subj\n";
        $head     .= "X-Mailer: PHPMail Tool\n";
        $head     .= "Mime-Version: 1.0\n";
        $head     .= "Content-Type:multipart/mixed;";
        $head     .= "boundary=\"----------".$un."\"\n\n";
        $zag       = "------------".$un."\nContent-Type:text/html;\n";
        $zag      .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
        $zag      .= "------------".$un."\n";
        if(isset($_FILES['file'])){
            $filename =    $_FILES['file']['name'];
            $file = $_FILES['file']['tmp_name'];
            $f         = fopen($file,"r+");

            $zag      .= "Content-Type: application/octet-stream;";
            $zag      .= "name=\"".basename($filename)."\"\n";
            $zag      .= "Content-Transfer-Encoding:base64\n";
            $zag      .= "Content-Disposition:attachment;";
            $zag      .= "filename=\"".basename($filename)."\"\n\n";
            $zag      .= chunk_split(base64_encode(fread($f,filesize($file))))."\n";
        }

        if (@mail("$to", "$subj", $zag, $head))
        {
            return new JsonResponse($response);
        }
        $response['success'] = false;
        $response['message'] = 'Error sending message';
        return new JsonResponse($response);
    }
}
