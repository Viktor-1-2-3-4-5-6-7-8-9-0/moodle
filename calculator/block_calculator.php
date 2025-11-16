<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use block_calculator\form\CalculatorForm;
use block_calculator\QuadraticSolver;
use block_calculator\CalculatorLog;
use block_calculator\output\CalculatorRenderer;

class block_calculator extends block_base {

    protected $result = null;
    protected $error = null;

    public function init(): void {
        $this->title = get_string('pluginname', 'block_calculator');
    }

    public function get_content(): stdClass {
        if ($this->content !== null) {
            return $this->content;
        }

        // Объявляем global ВНУТРИ метода
        global $PAGE, $SESSION;

        $this->content = new stdClass();
        $this->content->footer = '';

        $form = new CalculatorForm(null, $SESSION->block_calculator_arg, 'post', $PAGE->url->out(false));
        $this->handle_form_submission($form);

        $this->content->text = $this->render_calculator_ui($form);

        return $this->content;
    }

    /**
     * Обработка данных
     */
    protected function handle_form_submission(CalculatorForm $form): void {
        // Объявляем нужные глобальные переменные внутри метода
        global $USER, $SESSION, $PAGE;

        if (!empty($SESSION->block_calculator_result)) {
            $this->result = $SESSION->block_calculator_result;
            unset($SESSION->block_calculator_result);
            return;
        }

        if ($data = $form->get_data()) {
            try {
                $this->result = QuadraticSolver::solve($data->a, $data->b, $data->c);

                CalculatorLog::log_calculation((int)$USER->id, $data->a, $data->b, $data->c, $this->result);

                $SESSION->block_calculator_result =  $this->result;

                $SESSION->block_calculator_arg = (object)[
                    'a' => $data->a,
                    'b' => $data->b,
                    'c' => $data->c
                ];
                redirect($PAGE->url);
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    /**
     * UI блока
     */
    protected function render_calculator_ui(CalculatorForm $form): string {
        $html = html_writer::start_tag('div', [
        'class' => 'block-calculator p-3 text-center d-flex justify-content-center'
        ]);

        $html .= html_writer::start_tag('div', [
            'class' => 'calculator-container w-50 w-md-100'
        ]);

        $html .= $form->render();

        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        if ($this->error) {
            $html .= html_writer::div($this->error, 'alert alert-danger mt-3 w-100');
        } elseif ($this->result) {
            $html .= html_writer::div($this->result, 'alert alert-success mt-3 w-100');
        }

        $html .= html_writer::div(
            html_writer::link(
                new moodle_url('/blocks/calculator/history.php'),
                get_string('viewhistory', 'block_calculator'),
                ['class' => 'btn btn-outline-secondary btn-sm mt-2']
            ),
            'w-100 text-center'
        );

        return $html;
    }

    public function applicable_formats(): array {
        return ['all' => true];
    }
}