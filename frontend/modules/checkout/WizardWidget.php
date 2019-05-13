<?php


namespace frontend\modules\checkout;

use Yii;
use drsdre\wizardwidget\WizardWidget as ExtendWizardWidget;
use drsdre\wizardwidget\WizardWidgetAsset;
use yii\helpers\Html;

class WizardWidget extends ExtendWizardWidget
{


    public function init()
    {
        parent::init();
        echo Html::beginTag('div', [
            'class' => 'container checkout-content'
        ]);
        WizardWidgetAsset::register($this->getView());
    }

    public function run()
    {

        // Wizard line calculation
        $step_count = count($this->steps) + ($this->complete_content ? 1 : 0);
        $wizard_line_distribution = round(100 / $step_count); // Percentage
        $wizard_line_width = round(100 - $wizard_line_distribution); // Percentage

        $wizard_line = [];
        $tab_content = [];

        // Navigation tracker
        end($this->steps);
        $last_id = key($this->steps);

        $first = true;
        $class = '';

        foreach ($this->steps as $id => $step) {

            // Current or fist step is active, next steps are inactive (previous steps are available)
            if ($id == $this->start_step or (is_null($this->start_step) && $class == '')) {
                $class = 'active';
            } elseif ($class == 'active') {
                $class = 'disabled';
            }

            // Add icons to the wizard line
            $wizard_line[] = Html::tag(
                'li',
                '<i>' . $id . '</i>' .
                Html::tag('span', $step['title'], [
                    'data-toggle' => 'tab',
                    'data-href' => '#step' . $id,
                    'aria-controls' => 'step' . $id,
                    'role' => 'tab',
                    'title' => $step['title'],
                ]),
                array_merge(
                    [
                        'role' => 'presentation',
                        'class' => $class,
                        'style' => ['width' => $wizard_line_distribution . '%']
                    ],
                    isset($step['options']) ? $step['options'] : []
                )
            );
            $tab_content[] = Html::tag('div', $step['content'], [
                'class' => "step-{$id}-content",
                'id' => "step$id"
            ]);
        }


        // Start widget


        // Render the steps line
        echo Html::tag('ul', implode("\n", $wizard_line), [
            'class' => 'checkout-step',
            'role' => 'tablist'
        ]);

        echo Html::tag('div',implode("\n",$tab_content),[
            'class' => 'tab-content'
        ]);

        // Finish widget
        echo Html::endTag('div');
    }


    public function navButton($button_type, $step, $button_id)
    {
        // Setup a unique button id
        $options = ['id' => $button_id . $button_type];

        // Apply default button configuration if defined
        if (isset($this->default_buttons[$button_type]['options'])) {
            $options = array_merge($options, $this->default_buttons[$button_type]['options']);
        }

        // Apply step specific button configuration if defined
        if (isset($step['buttons'][$button_type]['options'])) {
            $options = array_merge($options, $step['buttons'][$button_type]['options']);
        }

        // Add navigation class
        if ($button_type == 'prev') {
            $options['class'] = $options['class'] . ' prev-step';
        } else {
            $options['class'] = $options['class'] . ' next-step';
        }

        // Display button
        if (isset($step['buttons'][$button_type]['html'])) {
            return $step['buttons'][$button_type]['html'];
        } elseif (isset($step['buttons'][$button_type]['title'])) {
            return Html::button($step['buttons'][$button_type]['title'], $options);
        } else {
            return Html::button($this->default_buttons[$button_type]['title'], $options);
        }
    }
}