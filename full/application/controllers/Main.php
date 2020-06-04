<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public function index($page = 'main')
    {
        if (!file_exists(APPPATH . 'views/' . $page . '.php')) {
            show_404();
        }
        $data['questions'] = $this->main_model->get_all_objects('questions');
        $data['answers'] = $this->main_model->get_all_objects('answers');
        $data['source_types'] = $this->main_model->get_all_objects('source_types');
        $this->load->view($page, $data);
    }

    public function send()
    {

        $fullname = $this->input->post('fullname', TRUE);
        $phone = $this->input->post('phone', TRUE);
        $email = $this->input->post('email', TRUE);
        $comment = $this->input->post('comment', TRUE);
        $source_type = $this->input->post('came-from', TRUE);
        $is_test = $this->input->post('test_results1');
        $friend_name = '';
        if ($source_type == 4) {
            $friend_name = $this->input->post('friend-name', TRUE);
        }

        if (empty($fullname)) {
            redirect('/', 'location');
        }

        if (recaptcha()) {
            $send_settings = $this->main_model->get_all_objects('settings')[0];
            if (!is_null($is_test)) {
                $correct_answers = $this->main_model->get_all_objects('questions');

                $questions_quantity = sizeof($correct_answers);
                for ($question_num = 1; $question_num <= $questions_quantity; $question_num++) {
                    $test_result[$question_num][0] = $this->input->post('test_results' . $question_num, TRUE);
                    $test_result[$question_num][1] = $correct_answers[$question_num - 1]['question'];
                    if ($correct_answers[$question_num - 1]['correct_answer']  == $test_result[$question_num][0]) {
                        $test_result[$question_num][2] = 'pass';
                    } else {
                        $test_result[$question_num][2] = 'fail';
                    }
                }
            } else {
                $test_result = NULL;
            }
            $data = [
                'name' => $fullname,
                'phone' => str_replace('-', '', $phone),
                'email' => $email,
                'comment' => $comment,
                'source_type' => $source_type,
                'friend' => $friend_name,
                'test_result' => json_encode($test_result)
            ];
            $this->main_model->push_respond($data);
            if ($source_type != 0) {
                $source_info = ['source_info' => $this->main_model->get_object_by_id('source_types', $source_type)];
                if ($source_type == 4) {
                    $source_info['source_info'] += ['friend' => $friend_name];
                }
                $data += $source_info;
            }            
            $this->main_model->send_email($send_settings, $data);
        }
    }
}
