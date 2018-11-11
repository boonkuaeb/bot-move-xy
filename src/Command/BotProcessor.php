<?php

namespace App\Command;

const NORTH = 'North';
const EAST = 'East';
const SOUTH = 'South';
const WEST = 'West';

class BotProcessor
{
    private $positionX;
    private $positionY;
    private $facing;

    public function __construct($positionX = 0, $positionY = 0, $facing = NORTH)
    {
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->facing = $facing;
    }

    public function processRoute($route)
    {
        if ($this->isTurnRight($route)) {
            $this->setTurnRight();
        } elseif ($this->isTurnLeft($route)) {
            $this->setTurnLeft();
        } else {
            $this->walkStepProcess($route);
        }
        return $this->printOutput();
    }

    private function isTurnRight($input)
    {
        if (strtoupper($input) == 'R'
        ) {
            return true;
        }
        return false;
    }

    private function setTurnRight()
    {
        switch ($this->facing) {
            case NORTH;
                $this->facing = EAST;
                break;
            case SOUTH:
                $this->facing = WEST;
                break;
            case EAST:
                $this->facing = SOUTH;
                break;
            case WEST:
                $this->facing = NORTH;
                break;
        }
    }

    private function isTurnLeft($input)
    {
        if (strtoupper($input) == 'L'
        ) {
            return true;
        }
        return false;
    }

    private function setTurnLeft()
    {
        switch ($this->facing) {
            case NORTH;
                $this->facing = WEST;
                break;
            case SOUTH:
                $this->facing = EAST;
                break;
            case EAST:
                $this->facing = NORTH;
                break;
            case WEST:
                $this->facing = SOUTH;
        }
    }

    private function walkStepProcess($walk_step)
    {
        switch ($this->facing) {
            case NORTH;
                $this->positionY = (double)$this->positionY + (double)$walk_step;
                break;
            case SOUTH:
                $this->positionY = (double)$this->positionY - (double)$walk_step;
                break;
            case EAST:
                $this->positionX = (double)$this->positionX + (double)$walk_step;
                break;
            case WEST:
                $this->positionX = (double)$this->positionX - (double)$walk_step;
                break;
        }
    }

    public function printOutput()
    {
        return "<info>X: " . $this->positionX . " Y: " . $this->positionY . " Direction: " . $this->facing . "</info>";
    }

    public function isWalking($input)
    {
        if (strtoupper($input) == 'W') {
            return true;
        }
        return false;
    }
}
