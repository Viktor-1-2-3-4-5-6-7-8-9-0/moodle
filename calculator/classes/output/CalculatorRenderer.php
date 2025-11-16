<?php
declare(strict_types=1);

namespace block_calculator\output;

use moodle_url;
use html_writer;

class CalculatorRenderer {

    /**
     * UI таблицу истории вычислений.
     */
    public static function render_history_table(array $logs): string {
        if (empty($logs)) {
            return html_writer::div(
                html_writer::tag('i', '', ['class' => 'fa fa-history me-2']) .
                get_string('nohistory', 'block_calculator'),
                'text-center text-muted py-4'
            );
        }

        $table = html_writer::start_tag('table', ['class' => 'table table-hover align-middle mb-0']);
        $table .= html_writer::start_tag('thead');
        $table .= html_writer::start_tag('tr');
        $table .= html_writer::tag('th', get_string('coefficients', 'block_calculator'), ['scope' => 'col']);
        $table .= html_writer::tag('th', get_string('result', 'block_calculator'), ['scope' => 'col', 'class' => 'text-end']);
        $table .= html_writer::end_tag('tr');
        $table .= html_writer::end_tag('thead');

        $table .= html_writer::start_tag('tbody');
        foreach ($logs as $log) {
            $coefficients = "a = {$log->a}, b = {$log->b}, c = {$log->c}";
            $table .= html_writer::start_tag('tr');
            $table .= html_writer::tag('td', $coefficients);
            $table .= html_writer::tag('td', html_writer::tag('code', $log->result), ['class' => 'text-end fw-semibold']);
            $table .= html_writer::end_tag('tr');
        }
        $table .= html_writer::end_tag('tbody');
        $table .= html_writer::end_tag('table');

        return $table;
    }
}