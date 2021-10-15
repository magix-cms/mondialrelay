<?php
require __DIR__ . '/php-mondialrelay-webservice-master/vendor/autoload.php';
include_once ('db.php');

use MondialRelay\Webservice;

class plugins_mondialrelay_public extends plugins_mondialrelay_db
{
    protected $template,
        $mail,
        $header,
        $data,
        $getlang,
        $modelDomain,
        $config,
        $settings,
        $about,
        $mollie,
        $message,
        $sanitize;

    public $purchase,
        $custom,
        $urlStatus,
        $payment_plugin = true,
        $callback,
        $order,
        $redirect;

    /**
     * plugins_hipay_public constructor.
     * @param null $t
     */
    public function __construct($t = null)
    {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->header = new http_header();
        $this->data = new frontend_model_data($this,$this->template);
        $this->getlang = $this->template->lang;
        $formClean = new form_inputEscape();
        $this->sanitize = new filter_sanitize();
        //$this->header = new component_httpUtils_header($this->template);
        $this->message = new component_core_message($this->template);
        //$this->mollie = new \Mollie\Api\MollieApiClient();
        $this->modelDomain = new frontend_model_domain($this->template);
        $this->about = new frontend_model_about($this->template);
        $formClean = new form_inputEscape();

        if (http_request::isPost('purchase')) {
            $this->purchase = $formClean->arrayClean($_POST['purchase']);
        }
        // ------ custom utilisé pour metadata
        /*if (http_request::isPost('custom')) {
            $this->custom = $formClean->arrayClean($_POST['custom']);
        }*/
        if (http_request::isGet('urlStatus')) {
            $this->urlStatus = $formClean->simpleClean($_GET['urlStatus']);
        }
        if (http_request::isGet('redirect')) {
            $this->redirect = $formClean->simpleClean($_GET['redirect']);
        }elseif (http_request::isPost('redirect')) {
            $this->redirect = $formClean->simpleClean($_POST['redirect']);
        }
        if (http_request::isPost('callback')) {
            $this->callback = $formClean->simpleClean($_POST['callback']);
        }
        /*if (http_request::isPost('order')) {
            $this->order = $formClean->simpleClean($_POST['order']);
        }*/
        $this->order = filter_rsa::tokenID();
        if (http_request::isPost('custom')) {
            $array = $_POST['custom'];
            $array['order'] = $formClean->simpleClean($this->order);
            $this->custom = $array;
        }
        if (http_request::isPost('mrData')) {
            $this->mrData = $formClean->arrayClean($_POST['mrData']);
        }
        //@ToDo switch to this declaration when deployed online
        //$this->mail = new frontend_model_mail($this->template, 'mollie');
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @return mixed
     */
    private function setItemsAccount(){
        return $this->getItems('root',NULL,'one',false);
    }
    /**
     * Update data
     * @param $data
     * @throws Exception
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'history':
                parent::insert(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }

    /**
     * Send a mail
     * @param $email
     * @param $tpl
     * @return bool
     */
    protected function send_email($email, $tpl, $data, $file = false) {
        if($email) {
            $this->template->configLoad();
            if(!$this->sanitize->mail($email)) {
                $this->message->json_post_response(false,'error_mail');
            }
            else {
                if($this->getlang) {
                    $contact = new plugins_contact_public();
                    $sender = $contact->getSender();

                    if(!empty($sender) && !empty($email)) {
                        $allowed_hosts = array_map(function($dom) { return $dom['url_domain']; },$this->modelDomain->getValidDomains());
                        if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
                            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
                            exit;
                        }
                        $noreply = 'noreply@'.str_replace('www.','',$_SERVER['HTTP_HOST']);

                        return $this->mail->send_email($email,$tpl,$data,'',$noreply,$sender['mail_sender'],$file);
                    }
                    else {
                        $this->message->json_post_response(false,'error_plugin');
                        return false;
                    }
                }
            }
        }
    }
    public function getPaymentStatus(){
        $mollie = $this->getItems('lastHistory',NULL,'one',false);
        return $mollie['status_h'];
    }
    /**
     *
     */
    public function run(){
        if(isset($this->purchase)) {
            $this->template->addConfigFile(
                array(component_core_system::basePath() . '/plugins/mondialrelay/i18n/'),
                array('public_local_'),
                false
            );
            $this->template->configLoad();

            $collection = $this->about->getCompanyData();
        }
        if(isset($_GET['order'])) {
            $data = $this->setItemsAccount();
            $this->template->assign('mondialrelay',$data);
            $this->template->display('mondialrelay/index.tpl');
        }else{
            if(isset($this->mrData)){
                $data = $this->setItemsAccount();
                // 1. Initialize Mondial Relay webservice with your credentials.
                $mondialrelay = new Webservice($data['merchant'], $data['privatekey']);
            }
        }
    }
}
?>