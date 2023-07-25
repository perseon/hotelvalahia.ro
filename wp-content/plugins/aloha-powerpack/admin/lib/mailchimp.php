<?php

/**
 * admin lib for processing backend events
 *
 */
class AlohaMailchimp {

    static $instance;

    public static function getInstance() {
        static $instance;
        $class = __CLASS__;
        if (!$instance instanceof $class) {
            $instance = new $class;
            $instance->init();
        }
        return $instance;
    }

    function loadAssets() {
        // Admin Scripts
        if (aloha_active_page('dashboard')) {
            $jsTimeModified = filemtime(ALOHA_ADMIN_JS_DIR . '/mailchimp.js');
            wp_register_script('aloha-mailchimp', ALOHA_ADMIN_JS_DIR_URL . '/mailchimp.js', ['jquery'], $jsTimeModified, true);
            wp_enqueue_script('aloha-mailchimp');
        }
    }

    function init() {
        //register stuff, load css js
        add_action('wp_ajax_aloha_subscribe_to_mailchimp', [$this, 'subscribeToMailchimp']);
        add_action('admin_enqueue_scripts', [$this, 'loadAssets']);
    }

    function getMailChimpFieldValue() {
        $result = $this->getMailChimpSubscription();
        if (!$result) {
            return get_bloginfo('admin_email');
        }
        return (string) $result;
    }

    function removeMailChimpSubscription() {
        return delete_option(ALOHA_OPTION_MAILCHIMP_SUBSCRIPTION);
    }

    function getMailChimpSubscription() {
        return get_option(ALOHA_OPTION_MAILCHIMP_SUBSCRIPTION);
    }

    function setMailChimpSubscription($email) {
        return update_option(ALOHA_OPTION_MAILCHIMP_SUBSCRIPTION, $email);
    }

    function subscribeToMailchimp() {
        $email = $_POST['email'];
        $ERROR = __('Some error occurred, try again later', ALOHA_DOMAIN);
        $SUCCESS = __('Subscribed successfully', ALOHA_DOMAIN);
        $response = array(
            'success' => false,
            'message' => $ERROR
        );
        if (!empty($email)) {
            $data = [
                'email' => $email,
                'status' => 'subscribed',
                'firstname' => '',
                'lastname' => ''
            ];

            // NOTE: status having 4 Option --"subscribed","unsubscribed","cleaned","pending"
            $res = $this->syncMailchimp($data);
            if ($res == 200) {
                $response['success'] = true;
                $response['message'] = $SUCCESS;
                $this->setMailChimpSubscription($email);
            }
        }

        wp_send_json($response);
    }

    function syncMailchimp($data) {
        $apiKey = ALOHA_MAILCHIMP_API_KEY;
        $listId = ALOHA_MAILCHIMP_LIST_ID;

        $memberId = md5(strtolower($data['email']));
        $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        $tags = [ALOHA_MAILCHIMP_ALOHA_TAG];
        $finalTags = apply_filters('aloha_mailchimp_tags', $tags);
        if (empty($finalTags) || !count($finalTags)) {
            return false;
        }
        
        $json = json_encode([
            'email_address' => $data['email'],
            'status' => $data['status'],
            'tags'  => $finalTags,
            'merge_fields' => [
                'FNAME' => $data['firstname'],
                'LNAME' => $data['lastname']
            ]
        ]);
        
        $auth = base64_encode('user:' . $apiKey);
        $response = wp_remote_post(
                $url,
                array(
                    'sslverify' => false,
                    'method' => 'PUT',
                    'timeout' => 10,
                    'redirection' => 1,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . $auth
                    ),
                    'body' => $json, // Payload, text to analyse
                    'data_format' => 'body'
                )
        );

        if (is_wp_error($response)) {
            $errorResponse = $response->get_error_message();
            return false;
        }
        
        //check if code is 200 if not then error
        if (isset($response['response']['code'])) {
            return $response['response']['code'];
        }

        return false;
    }

}

//initialize to register scripts and actions
$alohaMailchimp = AlohaMailchimp::getInstance();
