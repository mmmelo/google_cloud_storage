<?php
/**
 * Created by PhpStorm.
 * User: mmmelo
 * Date: 2/13/17
 * Time: 1:55 PM
 */

Class Google_storage extends WS_Controller{

    public function __construct(){

        parent::__construct();

        $this->bucketName = 'bucket-name';
        $this->load->model('Google_storage_model');

    }


    public function getFiles(){

        $result = $this->media_model->get_all_files();
        echo json_encode($result);
    }
    

    public function upload(){

        $file     = $this->input->post('files');
        $ext        = $this->input->post('ext');
        $result = $this->media_model->upload($file, $ext);
        echo json_encode($result);

    }

    public function delete(){
        $id     = $this->input->post('id');
        $result = $this->media_model->delete($id);
        echo json_encode($result);

    }
    
}
