<?php

namespace App\Src\Prediction;

class SetStatusByScore
{
    private $score = '';
    private $homeTeam = 0;
    private $awayTeam = 0;
    private $predictionIdentifier = '';
    private $status = -1;

    private $errors = [];

    public function __construct(string $score, string $predictionIdentifier)
    {
        $this->score = $score;
        $this->predictionIdentifier = $predictionIdentifier;
    }

    public function evaluateStatus()
    {
        $score = explode('-', $this->score);
        $this->homeTeam = isset($score[0]) ? $score[0] : null;
        $this->awayTeam = isset($score[1]) ? $score[1] : null;

        if (!ctype_digit($this->homeTeam) || !ctype_digit($this->awayTeam) || empty($this->predictionIdentifier)) {
            $this->errors[] = "Invalid score or empty prediction identifier";
            return false;
        }

        // 1x2
        if ($this->predictionIdentifier == 'equal')
            $this->status = ($this->homeTeam === $this->awayTeam) ? 1 : 2;

        // over / under
        if ($this->predictionIdentifier == 'over_1.5')
            $this->status = ($this->homeTeam + $this->awayTeam) > 1.5 ? 1 : 2;

        if ($this->predictionIdentifier == 'over_2.5')
            $this->status = ($this->homeTeam + $this->awayTeam) > 2.5 ? 1 : 2;

        if ($this->predictionIdentifier == 'over_3.5')
            $this->status = ($this->homeTeam + $this->awayTeam) > 3.5 ? 1 : 2;

        if ($this->predictionIdentifier == 'under_1.5')
            $this->status = ($this->homeTeam + $this->awayTeam) < 1.5 ? 1 : 2;

        if ($this->predictionIdentifier == 'under_2.5')
            $this->status = ($this->homeTeam + $this->awayTeam) < 2.5 ? 1 : 2;

        if ($this->predictionIdentifier == 'under_3.5')
            $this->status = ($this->homeTeam + $this->awayTeam) < 3.5 ? 1 : 2;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStatus() :int
    {
        return $this->status;
    }
}
