<?php
/**
* @file
* Contains \Drupal\ows\HomepageSettingsForm
*/
namespace Drupal\ows;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
/**
* Configure hello settings for this site.
*/
class HomepageSettingsForm extends ConfigFormBase {
    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'homepage_admin_settings';
    }
    /**
    * {@inheritdoc}
    */
    protected function getEditableConfigNames() {
        return [
        'ows.homepage.settings',
        ];
    }
    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('ows.homepage.settings');
        $form['message'] = array(
            '#type' => 'text_format',
            '#title' => $this->t('Message'),
            '#default_value' => $config->get('message')['value'],
            '#required' => true
        );

        return parent::buildForm($form, $form_state);
    }
    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('ows.homepage.settings')
        ->set('message', $form_state->getValue('message'))
        ->save();
        parent::submitForm($form, $form_state);
    }
}

