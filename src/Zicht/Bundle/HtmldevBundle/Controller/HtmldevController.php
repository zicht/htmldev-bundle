<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zicht\Bundle\HtmldevBundle\Service\DataLoaderInterface;

/**
 * Handles requests inside the Htmldev bundle.
 *
 * @Route(service="htmldev.htmldev_controller")
 */
class HtmldevController
{
    /** @var EngineInterface */
    private $templating;

    /** @var DataLoaderInterface */
    private $yamlLoader;

    /**
     * Initializes a new instance of the HtmldevController class.
     *
     * @param EngineInterface $templating
     * @param DataLoaderInterface $yamlLoader
     */
    public function __construct(EngineInterface $templating, DataLoaderInterface $yamlLoader)
    {
        $this->templating = $templating;
        $this->yamlLoader = $yamlLoader;
    }

    /**
     * @return Response
     *
     * @Route("/")
     */
    public function indexAction($section = 'colors')
    {
        $menuItems = $this->yamlLoader->loadData('data/styleguide', 'navigation.yml');
        if (count($menuItems) === 0) {
            throw new NotFoundHttpException();
        }

        return new RedirectResponse($menuItems[0]['uri']);
    }

    /**
     * @param string $section
     * @return Response
     *
     * @Route("/{section}", requirements={"section"=".+"})
     */
    public function showAction($section)
    {
        return $this->templating->renderResponse(sprintf('@htmldev/pages/%s.html.twig', $section));
    }
}
