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

    /** Tests for getSection() **/

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

        $c = [
            'name' => 'Over 2.5',
            'score' => '1-1',
            'prediction' => 'over_2.5',
            'expect' => 2,
        ];
        $table[] = $c;

        $c = [
            'name' => 'Over 2.5',
            'score' => '1-4',
            'prediction' => 'over_2.5',
            'expect' => 1,
        ];
        $table[] = $c;

        // over / under
        for($x = 1; $x <= 3; $x++) {
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
        }

        foreach ($table as $case) {
            $instance = new \App\Src\Prediction\SetStatusByScore($case['score'], $case['prediction']);
            $instance->evaluateStatus();
            $errors = $instance->getErrors();
            $this->assertTrue(empty($errors));
            $this->assertEquals(
                $instance->getStatus(),
                $case['expect'],
                $case['name'] . " --- Exp: " . $case['expect'] . " but get: " . $instance->getStatus()
            );
        }
    }


}
