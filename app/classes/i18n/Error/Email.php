<?php

/**
 * i18_Notice_Email
 *
 * Control your email translation keys.
 * 
 * @category  I18n
 * @package   Error_Email
 * @author    Your Name <yourname@domain.com>
 * @copyright 2009-2014 Your Company
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/translator
 */
Class I18n_Error_Email
{
    const MUST_BE_ARRAY         = 'email_must_be_array';
    const INVALID_ADDRESS       = 'email_invalid_address';
    const ATTACHMENT_MISSING    = 'email_attachment_missing';
    const ATTACHMENT_UNREADABLE = 'email_attachment_unreadable';
    const NO_RECIPIENTS         = 'email_no_recipients';
    const SEND_FAILURE_PHPMAIL  = 'email_send_failure_phpmail';
    const SEND_FAILURE_SENDMAIL = 'email_send_failure_sendmail';
    const SEND_FAILURE_SMTP     = 'email_send_failure_smtp';
    const SENT                  = 'email_sent';
    const NO_SOCKET             = 'email_no_socket';
    const NO_HOSTNAME           = 'email_no_hostname';
    const SMTP_ERROR            = 'email_smtp_error';
    const NO_SMTP_UNPW          = 'email_no_smtp_unpw';
    const FAILED_SMTP_LOGIN     = 'email_failed_smtp_login';
    const SMTP_AUTH_UN          = 'email_smtp_auth_un';
    const SMTP_AUTH_PW          = 'email_smtp_auth_pw';
    const SMTP_DATA_FAILURE     = 'email_smtp_data_failure';
    const EXIT_STATUS           = 'email_exit_status';
}