<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;

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


    /**
     * @Route("/htmldev/_form")
     * @Template
     */
    public function formAction()
    {
        $form = $this->createForm('htmldev_example');

        $form->addError(new FormError('This is an example form error message'));
        $form->get('htmldev_email')->addError(new FormError('This is an example form field error message'));
        $form->get('htmldev_select_dropdown')->addError(new FormError('This is an example form field error message related to choices'));
        $form->get('htmldev_checkbox_buttons')->addError(new FormError('This is an example form field error message related to choices'));
        $form->get('htmldev_checkbox')->addError(new FormError('This is an error message related to a single checkbox item'));

        return [
            'form' => $form->createView(),
            'form_url' => '#'
        ];
    }
}