<?php
/**
 * Created by PhpStorm.
 * User: boonkuaeboonsutta
 * Date: 12/11/18
 * Time: 00:04
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

const CMD = [
    '1' => 'R',
    '2' => 'L',
    '3' => 'W',
    'Q' => 'Q',
];
const CMD_LABEL = [
    "1" => 'Turn Right',
    "2" => 'Turn Left',
    "3" => 'Walk',
    "Q" => 'Exit'];

class BotCommand extends Command
{
    private $summaryResult = '';

    protected function configure()
    {
        $this->setName("Maqe:Bot")
            ->setDescription("MaqeBot version 1.0.2");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $bot = new BotProcessor();
        $this->summaryResult = $bot->printOutput();
        $output->writeln($this->summaryResult);
        $run = true;
        $helper = $this->getHelper('question');

        do {
            $questionCommand = $this->defineCommandChoice();
            $route = $helper->ask($input, $output, $questionCommand);
            $route = $this->translateNumberToAlpha($route);
            if ($route == 'Q') {
                $run = false;
                $this->Quite($output);
                return $output->writeln("____________________________");
            } else {
                if ($bot->isWalking($route)) {
                    $question = $this->enterWalkSteps();
                    $route = $helper->ask($input, $output, $question);
                }
                $result = $bot->processRoute($route);
                $this->summaryResult = $result;
                $output->writeln("____________________________");
                $output->writeln($result);
                $output->writeln("____________________________");
            }
        } while ($run);
    }

    private function defineCommandChoice()
    {
        $questionCommand = new ChoiceQuestion(
            'Please select your command (defaults to 3)', CMD_LABEL, '3'
        );
        $questionCommand->setNormalizer(function ($value) {
            return $value ? trim(strtoupper($value)) : '';
        });

        $questionCommand->setErrorMessage('Command %s is invalid.');
        return $questionCommand;
    }

    private function translateNumberToAlpha($route)
    {
        return CMD[$route];
    }

    /**
     * @param OutputInterface $output
     */
    private function Quite(OutputInterface $output)
    {
        $output->writeln("____________________________");
        $output->writeln("____________________________");
        $output->writeln($this->summaryResult);
        $output->writeln("Bye Bye!!");
    }

    /**
     * @return Question
     */
    private function enterWalkSteps()
    {
        $question = new Question('Please enter walking steps for this execution: ', 0);
        $question->setValidator(function ($answer) {
            if (!is_numeric($answer)) {
                throw new \RuntimeException('The walking steps should be an integer.');
            }
            return $answer;
        });
        return $question;
    }
}