<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HtmldevController
 *
 * @package Zicht\Bundle\HtmldevBundle\Controller
 */
class HtmldevController extends Controller
{
    /**
     * Renders the specified template
     *
     * @param string $filename
     * @return Response
     *
     * @Route("/{filename}", defaults={"filename"="index.html"}, requirements={"filename"="(?!_).*\.html"})
     * @Route("")
     */
    public function detailAction($filename = 'index.html')
    {
        $template = $this->get('htmldev.template');

        return $this->render(sprintf('@htmldev/%s', $template->find($filename)), array(
            'templates' => $template->findAll()
        ));
    }


    /**
     * Renders a form using the `htmldev_example` type. Including errors.
     *
     * @return array
     *
     * @Route("/_form")
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