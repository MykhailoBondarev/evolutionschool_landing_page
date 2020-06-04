<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_all_objects($table)
    {
        return $this->db->get($table)->result_array();
    }

    public function count_objects($table)
    {
        return $this->db->count_all_results($table);
    }

    public function push_respond($data)
    {
        $this->db->insert('contact_log', $data);
    }

    public function get_object_by_id($table, $id)
    {
        if ($id > 0) {
            return $this->db->get_where($table, ['id' => $id])->result_array()[0];
        }
    }

    public function send_email($send_settings = [], $data = [], $redirect_url = '', $show_pop_up = true)
    {
        $from_name = $send_settings['mail_from_name'];
        $to = $send_settings['mail_to'];
        if (!is_null($to)) {

            $from_email = $send_settings['mail_from'];
            $subject = $send_settings['subject'];
            $mailbody = createEmailHtml($data);
            $plain_mail_body = 'This is a text info';

            $this->load->library('email');
            $config['protocol'] = 'mail';
            $config['mailpath'] = '/usr/sbin/mail';
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $config['useragent'] = $from_name;
            $this->email->initialize($config);
            $this->email->from($from_email, $from_name);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($mailbody);
            $this->email->set_alt_message($plain_mail_body);
            if ($this->email->send() && $show_pop_up) {
                $this->session->set_flashdata('success_message', 'Благодарим, Ваше письмо успешно отправлено! Ожидайте, мы свяжемся с Вами как можно скорее.');
            }
            
            // Get and log mail send error

            $send_email_error = rtrim(str_replace('</pre>', '', str_replace('<pre>', '', $this->email->print_debugger())), " \n");

            $mail_errors_file = fopen('mail_errors.log', 'w') or die('Файл не найден!');
            fwrite(
                $mail_errors_file,
                $send_email_error
            );
            fclose($mail_errors_file);

            if ($send_email_error != '') {
                $this->session->set_flashdata('error_message', 'Ошибка при отправке сообщения!');
            }
            $this->email->clear();
            redirect($redirect_url, 'location');
        }
    }
}
