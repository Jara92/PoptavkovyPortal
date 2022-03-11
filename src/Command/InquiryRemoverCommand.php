<?php

namespace App\Command;

use App\Business\Operation\InquiryOperation;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class InquiryRemoverCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:inquiry-remover';
    protected static $defaultDescription = 'Removes old inquiries and sends notifications about inquiry removing.';

    public function __construct(
        private InquiryOperation $inquiryOperation
    )
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor

        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("<info>Removing inquiries...</info>");
        try {
            list($removed, $notified) = $this->inquiryOperation->handleOldInquiries();

            $output->writeln("<info>" . $removed . " inquiries have been removed.</info>");
            $output->writeln("<info>" . $notified . " notifications have been sent.</info>");

            return Command::SUCCESS;
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | TransportExceptionInterface $e) {
            $output->writeln("<error>" . $e->getMessage() . "</error>");
        }

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::FAILURE;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}