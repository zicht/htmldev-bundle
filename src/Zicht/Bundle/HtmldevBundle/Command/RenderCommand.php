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
    public function __construct(EngineInterface $templating, ContainerInterface $container)
    {
        parent::__construct();

        $this->templating = $templating;
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
        ;
    }


    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $this->container->enterScope('request');
        $request = new Request();
        $this->container->set('request', $request, 'request');
        $output->writeln($this->templating->render($input->getArgument('file')));
        $this->container->leaveScope('request');
    }
}