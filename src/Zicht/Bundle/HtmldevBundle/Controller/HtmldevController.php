<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class HtmldevController
 *
 * @package Zicht\Bundle\HtmldevBundle\Controller
 */
class HtmldevController extends Controller
{
    /**
     * @Route("/htmldev")
     * @Route("/htmldev/")
     *
     * @return Response
     */
    public function indexAction()
    {

        $template = $this->get('htmldev.template');

        return $this->render('@htmldev/_index.html.twig', array(
            'templates' => $template->findAll()
        ));
    }

    /**
     * @Route("/htmldev/{filename}")
     *
     * @param $filename
     * @return Response
     */
    public function detailAction($filename)
    {
        $template = $this->get('htmldev.template');

        return $this->render(sprintf('@htmldev/%s', $template->find($filename)), array(
            'templates' => $template->findAll()
        ));
    }
}