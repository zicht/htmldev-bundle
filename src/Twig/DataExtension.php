<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig_Extension;
use Twig_SimpleFunction;
use Zicht\Bundle\HtmldevBundle\Service\DataLoaderInterface;

/**
 * Twig extension for loading data.
 */
class DataExtension extends AbstractExtension
{
    /** @var DataLoaderInterface */
    private $yamlLoader;

    /**
     * @param DataLoaderInterface $yamlLoader
     */
    public function __construct(DataLoaderInterface $yamlLoader)
    {
        $this->yamlLoader = $yamlLoader;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('load_data', [$this, 'loadData']),
        ];
    }

    /**
     * Load data from a Yaml file in the htmldev data directory.
     *
     * @param string $type
     * @return array
     */
    public function loadData($type)
    {
        return $this->yamlLoader->loadData('data', sprintf('%s.yml', $type));
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_twig_data_extension';
    }
}
