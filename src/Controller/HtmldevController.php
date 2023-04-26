<?php declare(strict_types=1);

namespace Zicht\Bundle\HtmldevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use Zicht\Bundle\HtmldevBundle\Service\DataLoaderInterface;

/** Handles requests inside the Htmldev bundle. */
final class HtmldevController extends AbstractController
{
    private Environment $twig;

    private DataLoaderInterface $yamlLoader;

    private array $styleguideConfig = [];

    public function __construct(Environment $twig, DataLoaderInterface $yamlLoader)
    {
        $this->twig = $twig;
        $this->yamlLoader = $yamlLoader;
    }

    public function setStyleguideConfig(array $styleguideConfig)
    {
        $this->styleguideConfig = $styleguideConfig;
    }

    #[Route('/')]
    public function indexAction(?string $section = 'styleguide_intro'): Response
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

    #[Route('/{section}', requirements: ['section' => '.+'])]
    public function showAction(string $section): Response
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
