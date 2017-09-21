<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
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
     * Initializes a new instance of the HtmldevController class.
     *
     * @param PageService $template
     * @param Twig_Environment $twig
     * @param EngineInterface $templating
     */
    public function __construct(PageService $template, Twig_Environment $twig, EngineInterface $templating)
    {
        $this->template = $template;
        $this->twig = $twig;
        $this->templating = $templating;
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
}