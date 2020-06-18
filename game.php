<?php

const PAPER = 1;//1->2
const ROCK = 2;//2->3
const SCISSORS = 3;//3->1

interface BotInterface
{
    function makeChoice();
}

class BotPaper implements BotInterface
{
    function makeChoice()
    {
        return PAPER;
    }
}

class BotRandom implements BotInterface
{
    function makeChoice()
    {
        return rand(1, 3);
    }
}

class BotPlayer implements BotInterface
{
    function makeChoice()
    {
        print("make your choice 1 = paper 2 = rock 3 = scissors");
        return readline();
    }
}

class Game
{
    var $score = 0;
    var $history = [];
    private $playerA;
    private $playerB;

    function __construct(BotInterface $playerA, BotInterface $playerB)
    {
        $this->playerA = $playerA;
        $this->playerB = $playerB;
    }

    function playRound()
    {
        $result = $this->compareBets($this->playerA->makeChoice(), $this->playerB->makeChoice());
        $this->history[] = $result;
        $this->score += $result;
    }

    function compareBets(int $betA, int $betB): int
    {
        if ($betA == $betB) {
            return 0;
        }
        if ($betA == PAPER && $betB == ROCK) {
            return 1;
        }
        if ($betA == ROCK && $betB == SCISSORS) {
            return 1;
        }
        if ($betA == SCISSORS && $betB == PAPER) {
            return 1;
        }
        return -1;
    }
}

function play($rounds)
{
    $botA = new BotPaper();
    $botB = new BotRandom();
    $game = new Game($botA, $botB);
    $i = 0;
    while ($i < $rounds) {
        $i++;
        $game->playRound();
    }
    print_r($game->history);
    print ("TOTAL SCORE = {$game->score}, counted as playerA win count - playerB win count");
}

function playWithPlayer()
{
    $botA = new BotPlayer();
    $botB = new BotRandom();
    $game = new Game($botA, $botB);
    $game->playRound();
    print_r($game->history);
    print ("TOTAL SCORE = {$game->score}, counted as playerA win count - playerB win count");
}

play(100);
//playWithPlayer();

