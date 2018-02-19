<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SetStatusByScoreTest extends TestCase
{
    public function testShouldFailIFNotInstanceOf()
    {
        $this->assertInstanceOf(
            \App\Src\Prediction\SetStatusByScore::class,
            new \App\Src\Prediction\SetStatusByScore('', '')
        );
    }

    public function testShouldReturnLessThanZeroForEmptyScoreOrPredictionIdentifier()
    {
        $instance = new \App\Src\Prediction\SetStatusByScore('', '');
        $this->assertEquals($instance->getStatus(), -1);
    }

    public function testShouldReturnErrorForEmptyScoreOrPredictionIdentifier()
    {
        $instance = new \App\Src\Prediction\SetStatusByScore('', '');
        $instance->evaluateStatus();
        $this->assertEquals($instance->getStatus(), -1);

        $error = $instance->getErrors();
        $this->assertTrue(count($error) === 1);
    }

    public function testTableManyResults()
    {
        $table = [];

        // to score
        $c = [
            'name' => 'No Goal',
            'score' => '1-0',
            'prediction' => 'noGoal',
            'expect' => 2,
        ];
        $table[] = $c;
        $c = [
            'name' => 'No Goal',
            'score' => '0-1',
            'prediction' => 'noGoal',
            'expect' => 2,
        ];
        $table[] = $c;
        $c = [
            'name' => 'No Goal',
            'score' => '0-0',
            'prediction' => 'noGoal',
            'expect' => 1,
        ];
        $table[] = $c;

        $c = [
            'name' => 'Both To Score',
            'score' => '1-0',
            'prediction' => 'bothToScore',
            'expect' => 2,
        ];
        $table[] = $c;
        $c = [
            'name' => 'Both To Score',
            'score' => '0-0',
            'prediction' => 'bothToScore',
            'expect' => 2,
        ];
        $table[] = $c;
        $c = [
            'name' => 'Both To Score',
            'score' => '1-1',
            'prediction' => 'bothToScore',
            'expect' => 1,
        ];
        $table[] = $c;

        for($x = 1; $x <= 3; $x++) {

            // 1 x 2
            $c = [
                'name' => 'Team1',
                'score' => $x . '-' . ($x - 1),
                'prediction' => 'team1',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team1',
                'score' => ($x - 1) . '-' . $x,
                'prediction' => 'team1',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team1',
                'score' => $x . '-' . $x,
                'prediction' => 'team1',
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Equal',
                'score' => ($x - 1) . '-' . $x,
                'prediction' => 'equal',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Equal',
                'score' => $x . '-' . ($x - 1),
                'prediction' => 'equal',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Equal',
                'score' => $x . '-' . $x,
                'prediction' => 'equal',
                'expect' => 1,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team2',
                'score' => ($x - 1) . '-' . $x,
                'prediction' => 'team2',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team2',
                'score' => $x . '-' . ($x - 1),
                'prediction' => 'team2',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team2',
                'score' => $x . '-' . $x,
                'prediction' => 'team2',
                'expect' => 2,
            ];
            $table[] = $c;

            // over / under
            $c = [
                'name' => 'Over ' . $x . '.5',
                'score' => $x . '-' . $x,
                'prediction' => 'over_' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Over ' . $x . '.5',
                'score' => '0-' . $x,
                'prediction' => 'over_' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Under ' . $x . '.5',
                'score' => $x . '-' . $x,
                'prediction' => 'under_' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Under ' . $x . '.5',
                'score' => '0-' . $x,
                'prediction' => 'under_' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;

            // over under 1, 2, 3
            $c = [
                'name' => 'Over ' . $x,
                'score' => '0-' . $x,
                'prediction' => 'over_' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Over ' . $x,
                'score' => '1-' . $x,
                'prediction' => 'over_' . $x,
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Over ' . $x,
                'score' => '0-' . ($x - 1),
                'prediction' => 'over_' . $x,
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Under ' . $x,
                'score' => '0-' . $x,
                'prediction' => 'under_' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Under ' . $x,
                'score' => '1-' . $x,
                'prediction' => 'under_' . $x,
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Under ' . $x,
                'score' => '0-' . ($x - 1),
                'prediction' => 'under_' . $x,
                'expect' => 1,
            ];
            $table[] = $c;

            // ah
            $c = [
                'name' => 'Team 1 Ah +' . $x . '.5',
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_+' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah +' . $x . '.5',
                'score' => '0-' . ($x - 1),
                'prediction' => 'team1-ah_+' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Ah +' . $x . '.5',
                'score' => '0-' . ($x + 1),
                'prediction' => 'team1-ah_+' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 1 Ah -' . $x . '.5',
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_-' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah -' . $x . '.5',
                'score' => '0-' . ($x + 1),
                'prediction' => 'team1-ah_-' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah -' . $x . '.5',
                'score' => ($x + 1) . '-0',
                'prediction' => 'team1-ah_-' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 2 Ah +' . $x . '.5',
                'score' => $x . '-' . $x,
                'prediction' => 'team2-ah_+' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah +' . $x . '.5',
                'score' => ($x - 1) . '-0',
                'prediction' => 'team2-ah_+' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah +' . $x . '.5',
                'score' => ($x + 1) . '-0',
                'prediction' => 'team2-ah_+' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 2 Ah -' . $x . '.5 equal',
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_-' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah -' . $x . '.5',
                'score' => ($x + 1) . '-0',
                'prediction' => 'team2-ah_-' . $x . '.5',
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah -' . $x . '.5',
                'score' => '0-' . ($x + 1),
                'prediction' => 'team2-ah_-' . $x . '.5',
                'expect' => 1,
            ];
            $table[] = $c;

            // ah 1, 2, 3 not work with 0
            if ($x == 0)
                continue;

            $c = [
                'name' => 'Team 1 Ah +' . $x,
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_+' . $x,
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah +' . $x . '.5',
                'score' => '0-' . $x,
                'prediction' => 'team1-ah_+' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Ah +' . $x . '.5',
                'score' => '0-' . ($x + 1),
                'prediction' => 'team1-ah_+' . $x,
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 2 Ah +' . $x,
                'score' => $x . '-' . $x,
                'prediction' => 'team2-ah_+' . $x,
                'expect' => 1,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah +' . $x,
                'score' => $x . '-0',
                'prediction' => 'team2-ah_+' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah +' . $x,
                'score' => ($x + 1) . '-0',
                'prediction' => 'team2-ah_+' . $x,
                'expect' => 2,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 1 Ah -' . $x,
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_-' . $x,
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah -' . $x,
                'score' => $x . '-0',
                'prediction' => 'team1-ah_-' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 1 Ah -' . $x,
                'score' => ($x + 1) . '-0',
                'prediction' => 'team1-ah_-' . $x,
                'expect' => 1,
            ];
            $table[] = $c;

            $c = [
                'name' => 'Team 2 Ah -' . $x,
                'score' => $x . '-' . $x,
                'prediction' => 'team1-ah_-' . $x,
                'expect' => 2,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah -' . $x,
                'score' => '0-' . $x,
                'prediction' => 'team2-ah_-' . $x,
                'expect' => 3,
            ];
            $table[] = $c;
            $c = [
                'name' => 'Team 2 Ah -' . $x,
                'score' => '0-' . ($x + 1),
                'prediction' => 'team2-ah_-' . $x,
                'expect' => 1,
            ];
            $table[] = $c;
        }

        foreach ($table as $case) {
            $instance = new \App\Src\Prediction\SetStatusByScore($case['score'], $case['prediction']);
            $instance->evaluateStatus();
            $errors = $instance->getErrors();
            if (!empty($errors))
                print_r($errors);
            $this->assertTrue(empty($errors));
            $this->assertEquals(
                $case['expect'],
                $instance->getStatus(),
                $case['name'] . " --- Exp: " . $case['expect'] . " but get: " . $instance->getStatus()
            );
        }
    }
}
