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

    /** @var array */
    private $assets = [];

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
     * @param array $assets
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;
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
        $templates = [
            sprintf('@htmldev/pages/%s.html.twig', $section),                 // Override: Custom section specific template
            sprintf('ZichtHtmldevBundle::styleguide/%s.html.twig', $section), // Section specific template
            'ZichtHtmldevBundle:styleguide:component.html.twig',              // No custom template, use general component template
            null, // Final value when nothing is found
        ];
        do {
            $template = $templates[(isset($i) ? ++$i : $i = 0)];
        } while ($i + 1 < count($templates) && !$this->templating->exists($template));


        if (null === $template) {
            throw new \UnexpectedValueException(sprintf('Can\'t find a template to render the "%s" section', $section));
        }

        return $this->templating->renderResponse(
            $template,
            ['section' => $section, 'name' => array_reverse(explode('/', $section))[0], 'assets' => $this->assets]
        );
    }
}
