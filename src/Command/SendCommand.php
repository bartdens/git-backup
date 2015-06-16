<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Parser;
use GitElephant\Repository;

class SendCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('git:send')
            ->setDescription('Commits all changes to the repository')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // read the config
        $yaml   = new Parser();
        $config = $yaml->parse(file_get_contents(__DIR__.'../../config/parameters.yml'));

        $config = $config['SendCommand'];

        $excluded = $config['directories']['exclude'];
        $included = $config['directories']['include'];

        // find subfolders in given directories
        $finder = new Finder();

        foreach ($included as $scanDir) {
            $subFolders = $finder
                ->depth('== 0')
                ->directories()
                ->in($scanDir)
                ->exclude($excluded)
            ;

            /** @var \Symfony\Component\Finder\SplFileInfo $directory */
            foreach ($subFolders as $directory) {
                try {
                    $repo = new Repository($directory->getPathname());
                    $status = $repo->getStatus();
                    if ($status->all()->count()) {
                        $repo->commit('auto commit of '.date('Ymd Hi'), true);
                    }
                    $repo->push();
                } catch (\Exception $e) {
                    // not a repo, ignore
                    continue;
                }
            }
        }
    }
}