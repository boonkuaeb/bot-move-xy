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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;


class BotCommand extends Command
{
    private $summaryResult ='';
    protected function configure()
    {
        $this->setName("Maqe:Bot")
            ->setDescription("MaqeBot version 1.0.2");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $bot = new BotProcessor();
        $this->summaryResult= $bot->printOutput();
        $output->writeln($this->summaryResult);
        $run = true;
        do {
            $helper = $this->getHelper('question');
            $questionCommand = new ChoiceQuestion(
                'Please select your command (defaults to W)',
                array('R' => 'Turn Right', 'L' => 'Turn Left', 'W' => 'Walk', 'Q' => 'Exit'),
                'W'
            );
            $questionCommand->setNormalizer(function ($value) {
                return $value ? trim(strtoupper($value)) : '';
            });

            $questionCommand->setErrorMessage('Command %s is invalid.');
            $route = $helper->ask($input, $output, $questionCommand);

            if ($route == 'Q') {
                $run = false;
                $this->Quite($output);
                return $output->writeln("____________________________");
            } else {
                if($bot->isWalking($route))
                {
                    $question = new Question('Please enter walking steps for this execution: ', 0);
                    $question->setValidator(function ($answer) {
                        if (!is_numeric($answer)) {
                            throw new \RuntimeException('The walking steps should be an integer.');
                        }
                        return $answer;
                    });

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

    /**
     * @param OutputInterface $output
     */
    protected function Quite(OutputInterface $output)
    {
        $output->writeln("____________________________");
        $output->writeln("____________________________");
        $output->writeln($this->summaryResult);
        $output->writeln("Bye Bye!!");
    }

}