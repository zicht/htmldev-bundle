<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Zicht\Bundle\HtmldevBundle\Service\DataLoaderInterface;

/**
 * Handles requests inside the Htmldev bundle.
 */
class HtmldevController extends AbstractController
{
    /** @var Environment */
    private $twig;

    /** @var DataLoaderInterface */
    private $yamlLoader;

    /** @var array */
    private $styleguideConfig = [];

    /**
     * @param Environment $twig
     * @param DataLoaderInterface $yamlLoader
     */
    public function __construct(Environment $twig, DataLoaderInterface $yamlLoader)
    {
        $this->twig = $twig;
        $this->yamlLoader = $yamlLoader;
    }

    /**
     * @param array $styleguideConfig
     */
    public function setStyleguideConfig(array $styleguideConfig)
    {
        $this->styleguideConfig = $styleguideConfig;
    }

    /**
     * @param string|null $section
     * @return Response
     *
     * @Route("/")
     */
    public function indexAction($section = 'styleguide_intro')
    {
        if (null !== $section && '' !== $section && $this->twig->getLoader()->exists(sprintf('@htmldev/pages/%s.html.twig', $section))) {
            return $this->forward(self::class . '::showAction', ['section' => $section]);
        }

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
            sprintf('@htmldev/pages/%s.html.twig', $section),           // Override: Custom section specific template
            sprintf('@ZichtHtmldev/styleguide/%s.html.twig', $section), // Section specific template
            '@ZichtHtmldev/styleguide/component.html.twig',             // No custom template, use general component template
            null, // Final value when nothing is found
        ];
        $template = null;
        foreach ($templates as $template) {
            if ($this->twig->getLoader()->exists($template)) {
                break;
            }
        }

        if ($template === null) {
            throw new \UnexpectedValueException(sprintf('Can\'t find a template to render the "%s" section', $section));
        }

        return new Response(
            $this->twig->render(
                $template,
                [
                    'section' => $section,
                    'name' => array_reverse(explode('/', $section))[0],
                    'styleguide' => (object)$this->styleguideConfig,
                ]
            )
        );
    }
}
