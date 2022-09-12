<?php declare(strict_types=1);

namespace Bref\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class Init extends Command
{
    protected function configure(): void
    {
        $this->setName('init');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filesToGitAdd = [];
        $exeFinder = new ExecutableFinder;
        if (! $exeFinder->find('serverless')) {
            $io->warning(
                'The `serverless` command is not installed.' . PHP_EOL .
                'You will not be able to deploy your application unless it is installed' . PHP_EOL .
                'Please follow the instructions at https://bref.sh/docs/installation.html' . PHP_EOL .
                'If you have the `serverless` command available elsewhere (eg in a Docker container) you can ignore this warning'
            );
        }

        $choice = $io->choice(
            'What kind of lambda do you want to create? (you will be able to add more functions later by editing `serverless.yml`)',
            [
                'Web application',
                'Event-driven function',
            ],
            'Web application',
        );
        $templateDirectory = [
            'Web application' => 'http',
            'Event-driven function' => 'function',
        ][$choice];

        $fs = new Filesystem;
        $rootPath = dirname(__DIR__, 3) . "/template/$templateDirectory";

        $io->writeln('Creating index.php');
        if (file_exists('index.php')) {
            $io->warning('The directory already contains a `index.php` file. Skipping...');
        }
        else {
            $fs->copy("$rootPath/index.php", 'index.php');
            $filesToGitAdd[] = 'index.php';
        }

        $io->writeln('Creating serverless.yml');
        if (file_exists('serverless.yml')) {
            $io->warning('The directory already contains a `serverless.yml` file. Skipping...');
        }
        else {
            $template = file_get_contents("$rootPath/serverless.yml");
            $template = str_replace('PHP_VERSION', PHP_MAJOR_VERSION . PHP_MINOR_VERSION, $template);
            file_put_contents('serverless.yml', $template);
            $filesToGitAdd[] = 'serverless.yml';
        }

        /*
         * We check if this is a git repository to automatically add files to git.
         */
        if ((new Process(['git', 'rev-parse', '--is-inside-work-tree']))->run() === 0) {
            foreach ($filesToGitAdd as $file) {
                (new Process(['git', 'add', $file]))->run();
            }
            $io->success([
                'Project initialized and ready to test or deploy.',
                'The files created were automatically added to git.',
            ]);
        } else {
            $io->success('Project initialized and ready to test or deploy.');
        }

        return 0;
    }
}
