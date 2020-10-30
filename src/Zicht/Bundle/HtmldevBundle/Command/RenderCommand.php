<?php
/**
 * @author Gerard van Helden <gerard@zicht.nl>
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Command;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Console;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Renders a template.
 */
class RenderCommand extends Console\Command\Command
{
    /**
     * Constructor
     *
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    /**
     * Configuring
     *
     *
     */
    protected function configure()
    {
        $this
            ->setName('zicht:htmldev:render')
            ->addArgument('file', Console\Input\InputArgument::REQUIRED, 'The template to render')
            ->addOption('output', 'o', Console\Input\InputOption::VALUE_REQUIRED, 'Specify output file, default is stdout', '-');
        ;
    }


    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $this->container->enterScope('request');
        $request = new Request();
        $this->container->set('request', $request, 'request');
        $twig = $this->container->get('twig');
        $outputFile = $input->getOption('output');
        if ($outputFile === '-') {
            $outputFile = 'php://stdout';
        }
        try {
            $filename = basename($input->getArgument('file'), '.twig');
            $twig->addGlobal('htmldev_templatename', $filename);
            $rendered = $twig->render($input->getArgument('file'));
            file_put_contents($outputFile, $rendered);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $this->container->leaveScope('request');
    }
}