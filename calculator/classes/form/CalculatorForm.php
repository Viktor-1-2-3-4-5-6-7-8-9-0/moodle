<?php

namespace block_calculator\form;

use moodleform;

class CalculatorForm extends moodleform {

    protected function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata ?? (object)[];

        $mform->addElement('text', 'a', 'a');
        $mform->setType('a', PARAM_FLOAT);
        $mform->addRule('a', get_string('required'), 'required');
        $mform->setDefault('a', $customdata->a ?? 0);

        $mform->addElement('text', 'b', 'b');
        $mform->setType('b', PARAM_FLOAT);
        $mform->addRule('b', get_string('required'), 'required');
        $mform->setDefault('b', $customdata->b ?? 0);

        $mform->addElement('text', 'c', 'c');
        $mform->setType('c', PARAM_FLOAT);
        $mform->addRule('c', get_string('required'), 'required');
        $mform->setDefault('c', $customdata->c ?? 0);

        $this->add_action_buttons(false, get_string('calculate', 'block_calculator'));

        $mform->updateAttributes(['class' => 'calculator-form']);
    }
}