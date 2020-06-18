<?php

const PAPER = 1;//1->2
const ROCK = 2;//2->3
const SCISSORS = 3;//3->1
const POSSIBLE_BETS = ["BetPaper", "BetRock", "BetScissors"];

class Bet
{
    var $id;
    var $strongerBets;

    function compare(Bet $bet)
    {
        if ($this->id == $bet->id) {
            return 0;
        }
        if (in_array($bet->id, $this->strongerBets)) {
            return -1;
        }
        return 1;
    }
}

Class BetPaper extends Bet
{
    var $id = PAPER;
    var $strongerBets = [SCISSORS];
}

Class BetRock extends Bet
{
    var $id = ROCK;
    var $strongerBets = [PAPER];
}

Class BetScissors extends Bet
{
    var $id = SCISSORS;
    var $strongerBets = [ROCK];
}

interface BotInterface
{
    function makeChoice(): Bet;
}

class BotPaper implements BotInterface
{
    function makeChoice(): Bet
    {
        return new BetPaper();
    }
}

class BotRandom implements BotInterface
{
    function makeChoice(): Bet
    {
        $className = POSSIBLE_BETS[rand(0, count(POSSIBLE_BETS) - 1)];
        return new $className();
    }
}

class BotPlayer implements BotInterface
{
    function makeChoice(): Bet
    {
        print("make your choice, input single digit number, where");
        print_r(POSSIBLE_BETS);
        $className = POSSIBLE_BETS[readline()];
        return new $className();
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
        $result = $this->playerA->makeChoice()->compare($this->playerB->makeChoice());
        $this->history[] = $result;
        $this->score += $result;
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

