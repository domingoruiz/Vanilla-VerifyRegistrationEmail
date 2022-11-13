<?php if (!defined('APPLICATION')) exit();

class VerifyRegistrationEmailPlugin extends Gdn_Plugin {

    public function entryController_registerValidation_handler($sender) {
        if (!$this->emailisCorrect($sender->Form->getValue('Email'))) {
            $sender->Form->addError(Gdn::translate('The e-mail domain is not among those allowed.'));
            $sender->render();
            exit();
        }
    }


    public function settingsController_VerifyRegistrationEmail_create($sender) {
        $sender->permission('Garden.Settings.Manage');
        $sender->title(Gdn::translate('Verify registration email'));

        $conf = new ConfigurationModule($sender);
        $conf->initialize([
            'VerifyRegistrationEmail.Domains' => [
                'Control' => 'textbox',
                'LabelCode' => Gdn::translate('Accepted domain'),
                'Description' => Gdn::translate('Write the accepted domains separated by commas'),
                'Default' => 'gmail.com,outlook.com,yahoo.com'
            ]
        ]);

        $conf->renderAll();
    }

    private function emailisCorrect($attempt = '') {
        $domains = explode(',', t(c('VerifyRegistrationEmail.Domains', 'gmail.com,outlook.com,yahoo.com')));
        $attempt = explode("@", $attempt)[1];

        foreach ($domains as $domain) {
            if (strcasecmp(trim($domain), $attempt) == 0) {
                return true;
            }
        }

        return false;
    } 
}
