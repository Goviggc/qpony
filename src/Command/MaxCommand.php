<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;
use App\Utils\MaxNumber;

class MaxCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = "max";

    /**
     * @var array
     */
    protected $askedNumbers = [];

    protected function configure(): void
    {
        $this
            ->setDescription("Get max value");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     * @param int $id
     */
    protected function ask(InputInterface $input, OutputInterface $output, QuestionHelper $helper, int $id = 1): void
    {
        $question = new Question("Podaj liczbę n (Enter aby zaończyć): ", "");
        $number = $helper->ask($input, $output, $question);

        if($number === "" || (int)$number === 0)
            return;

        array_push($this->askedNumbers, [$id, (int)$number]);

        $this->ask($input, $output, $helper, ++$id);
    }

    /**
     * @return array
     */
    protected function getRows(): array
    {
        return array_map(function($a) {
            $max = new MaxNumber($a[1]);

            return [$a[0], $a[1], $max->getMaxNumber()];
        }, $this->askedNumbers);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $this->ask($input, $output, $helper);

        if(count($this->askedNumbers)){
            $output->writeln("\nTwój wynik to:");

            $table = new Table($output);
            $table->setStyle("box-double");
            $table->setHeaders(["#", "i", "a"]);
            $table->setRows($this->getRows());
            $table->render();
            return;
        }

        $output->writeln("Nie podano żadnych wartości");
    }
}