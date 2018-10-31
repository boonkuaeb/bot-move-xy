#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

(new Application('Bot', '1.0.0'))
    ->register('move')
    ->addArgument('route', InputArgument::REQUIRED, 'Route')
    ->addOption('debug', null, InputOption::VALUE_OPTIONAL, 'debug')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $route = $input->getArgument('route');
        $debug = $input->getOption('debug');

        $result = processRoute($route, $output, $debug);
        $output->writeln("<info>============================</info>");
        $output->writeln($result);
        return $output->writeln("<info>============================</info>");
    })
    ->getApplication()
    ->run();

/**
 * @param $route
 * @param OutputInterface $output
 * @param null $debug
 * @return string
 */
function processRoute($route, $output, $debug = null)
{
    if (!is_null($debug)) {
        $output->writeln("<info>============================</info>");
        $output->writeln($route);
        $output->writeln("<info>============================</info>");
    }
    //North South East West
    $position_x = 0;
    $position_y = 0;
    $facing = 'North';
    $extract_route = str_split($route);
    for ($i = 0; $i < count($extract_route); $i++) {
        if ($extract_route[$i] == 'R') {
            $facing = TurnRight($facing);
        }
        if ($extract_route[$i] == 'L') {
            $facing = TurnLeft($facing);
        }
        if ($extract_route[$i] == 'W') {
            $walk_step = SetWalkStep($output, $debug, $i, $extract_route, $facing);
            list($position_x, $position_y) = WalkStepProcess($facing, $position_x, $walk_step, $position_y);
        }
    }
    return "<info>X: $position_x Y: $position_y Direction: $facing</info>";

}

/**
 * @param $output
 * @param $debug
 * @param $i
 * @param $extract_route
 * @param $facing
 * @return string
 */
function SetWalkStep($output, $debug, $i, $extract_route, $facing)
{
    $walk_step = '';
    for ($j = $i + 1; $j < count($extract_route); $j++) {

        if (preg_match("/^[0-9]+$/i", $extract_route[$j])) {
            $walk_step = $walk_step . $extract_route[$j];
        } else {
            break;
        }
    }
    if (!is_null($debug)) {
        $output->writeln("<info>Go to $facing , $walk_step steps</info>");
    }
    return $walk_step;
}

/**
 * @param $facing
 * @param $position_x
 * @param $walk_step
 * @param $position_y
 * @return array
 */
function WalkStepProcess($facing, $position_x, $walk_step, $position_y)
{
    switch ($facing) {
        case 'West':
            $position_x = (double)$position_x - (double)$walk_step;
            break;
        case 'East':
            $position_x = (double)$position_x + (double)$walk_step;
            break;
        case 'South':
            $position_y = (double)$position_y - (double)$walk_step;
            break;
        case 'North';
            $position_y = (double)$position_y + (double)$walk_step;
            break;
    }
    return array($position_x, $position_y);
}

function TurnRight($facing)
{
    switch ($facing) {
        case 'North';
            return 'East';
        case 'South':
            return 'West';
        case 'East':
            return 'South';
        case 'West':
            return 'North';
    }
}

function TurnLeft($facing)
{
    switch ($facing) {
        case 'North';
            return 'West';
        case 'South':
            return 'East';
        case 'East':
            return 'North';
        case 'West':
            return 'South';
    }
}


