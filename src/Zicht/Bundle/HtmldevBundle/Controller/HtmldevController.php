<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use \Twig_Environment;
use Zicht\Bundle\HtmldevBundle\Service\Template as PageService;

/**
 * Class HtmldevController
 *
 * @Route(service="htmldev.htmldev_controller")
 */
class HtmldevController
{
    /**
     * @var PageService
     */
    private $template;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Initializes a new instance of the HtmldevController class.
     *
     * @param PageService $template
     * @param Twig_Environment $twig
     * @param EngineInterface $templating
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(PageService $template, Twig_Environment $twig, EngineInterface $templating, FormFactoryInterface $formFactory)
    {
        $this->template = $template;
        $this->twig = $twig;
        $this->templating = $templating;
        $this->formFactory = $formFactory;
    }

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
        $this->twig->addGlobal('htmldev_templatename', $filename);
        return $this->templating->renderResponse(sprintf('@htmldev/%s', $this->template->find($filename)), array(
            'templates' => $this->template->findAll()
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
        $form = $this->formFactory->create('htmldev_example');

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