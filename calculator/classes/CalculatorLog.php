<?php
declare(strict_types=1);

namespace block_calculator;

class CalculatorLog {
    /**
     * Сохранение данных в бд.
     */
    public static function log_calculation(int $userid, float $a, float $b, float $c, string $result): void {
        global $DB;

        $record = (object)[
            'userid' => $userid,
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'result' => $result,
            'timecreated' => time(),
        ];

        $DB->insert_record('block_calculator_log', $record);
    }

    /**
     * Получение данных из бд.
     */
    public static function get_user_logs(int $userid, int $limit = 10): array {
        global $DB;
        return $DB->get_records('block_calculator_log', ['userid' => $userid], 'timecreated DESC', '*', 0, $limit);
    }
}