<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();

use block_calculator\CalculatorLog;
use block_calculator\output\CalculatorRenderer;


/**
 * Определяет URL для кнопки "Назад".
 */
function block_calculator_get_back_url(): moodle_url {
    global $CFG;

    if (!empty($_SERVER['HTTP_REFERER'])) {
        $referer = clean_param($_SERVER['HTTP_REFERER'], PARAM_URL);
        if (strpos($referer, $CFG->wwwroot) === 0) {
            return new moodle_url($referer);
        }
    }

    return new moodle_url('/my/');
}


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/calculator/history.php');
$PAGE->set_title(get_string('pluginname', 'block_calculator'));
$PAGE->set_heading(get_string('history', 'block_calculator'));

echo $OUTPUT->header();

echo html_writer::start_div('container mt-4');
echo html_writer::start_div('card shadow-sm border-0');

// Заголовок
echo html_writer::start_div('card-header bg-primary text-white');
echo html_writer::tag('h2', get_string('history', 'block_calculator'), ['class' => 'h5 mb-0']);
echo html_writer::end_div();

// Тело
echo html_writer::start_div('card-body');
$logs = CalculatorLog::get_user_logs((int)$USER->id, 10);
echo CalculatorRenderer::render_history_table($logs);
echo html_writer::end_div();

// Кнопка "Назад"
$backurl = block_calculator_get_back_url();
echo html_writer::div(
    html_writer::link($backurl, get_string('back', 'block_calculator'), ['class' => 'btn btn-outline-primary']),
    'card-footer text-center'
);

echo html_writer::end_div();
echo html_writer::end_div(); 

echo $OUTPUT->footer();