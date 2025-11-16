<?php
declare(strict_types=1);

namespace block_calculator;

use moodle_exception;

class QuadraticSolver {

    private const EPS = 1e-10;

    public static function solve(float $a, float $b, float $c): string {
        if (abs($a) >= self::EPS) {
            // Да, квадратное
            $d = $b * $b - 4 * $a * $c;

            if ($d > self::EPS) {
                $x1 = (-$b + sqrt($d)) / (2 * $a);
                $x2 = (-$b - sqrt($d)) / (2 * $a);
                return get_string('tworoots', 'block_calculator', [
                    'x1' => round($x1, 6),
                    'x2' => round($x2, 6)
                ]);
            } elseif (abs($d) < self::EPS) {
                $x = -$b / (2 * $a);
                return get_string('oneroot', 'block_calculator', ['x' => round($x, 6)]);
            } else {
                return get_string('noroots', 'block_calculator', ['d' => round($d, 6)]);
            }
        }

        if (abs($b) >= self::EPS) {
            $x = -$c / $b;
            return get_string('linear_root', 'block_calculator', ['x' => round($x, 6)]);
        }


        if (abs($c) < self::EPS) {
            return get_string('infinite_solutions', 'block_calculator');
        } else {
            return get_string('no_solution', 'block_calculator', ['c' => round($c, 6)]);
        }
    }
}