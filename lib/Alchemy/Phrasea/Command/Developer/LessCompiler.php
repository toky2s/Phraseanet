<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Command\Developer;

use Alchemy\Phrasea\Command\Command;
use Alchemy\Phrasea\Utilities\Compiler\RecessLessCompiler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command builds less file
 */
class LessCompiler extends Command
{
    private $recessLessCompiler;

    public function __construct($recessLessCompiler = null)
    {
        parent::__construct('assets:compile-less');

        $this->setDescription('Compile less files');


        $this->recessLessCompiler = $recessLessCompiler ?: new RecessLessCompiler();
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $copies = array(
            $this->container['root.path'] . '/www/assets/bootstrap/img/glyphicons-halflings-white.png' => $this->container['root.path'] . '/www/skins/build/bootstrap/img/glyphicons-halflings-white.png',
            $this->container['root.path'] . '/www/assets/bootstrap/img/glyphicons-halflings.png' => $this->container['root.path'] . '/www/skins/build/bootstrap/img/glyphicons-halflings.png',
        );

        foreach ($copies as $source => $target) {
            $this->container['filesystem']->mkdir(dirname($target));
            $this->container['filesystem']->copy($source, $target);
        }

        $files = array(
            $this->container['root.path'] . '/www/skins/login/less/login.less' => $this->container['root.path'] . '/www/skins/build/login.css',
            $this->container['root.path'] . '/www/skins/account/account.less' => $this->container['root.path'] . '/www/skins/build/account.css',
            $this->container['root.path'] . '/www/assets/bootstrap/less/bootstrap.less' => $this->container['root.path'] . '/www/skins/build/bootstrap/css/bootstrap.css',
            $this->container['root.path'] . '/www/assets/bootstrap/less/responsive.less' => $this->container['root.path'] . '/www/skins/build/bootstrap/css/bootstrap-responsive.css',
        );

        $output->writeln('Building Assets...');

        if (false === $this->container['phraseanet.less-builder']->build($files)) {
            $output->writeln(sprintf('<error>Errors occured during the build %s</error>', implode(', ', $this->container['phraseanet.less-builder']->getErrors())));

            return 1;
        }

        $output->writeln('<info>Build done !</info>');

        return 0;
    }
}
