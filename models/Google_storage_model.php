<?php
defined('BASEPATH') OR exit('No direct script access allowed');


include_once GOOGLE_CLIENT . 'autoload.php';
use Google\Cloud\Storage\StorageClient;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . GOOGLE_APPLICATION_CREDENTIALS);


class Google_storage_model extends CI_Model{
    
    function __construct(){

        parent::__construct();
        $this->bucketName = 'bucket-name';
    }
    
    public function get_all_files(){
        $result =  array(
            "success" => true
        );

        $storage = new StorageClient();

        $bucket = $storage->bucket($this->bucketName);

        foreach ($bucket->objects() as $object) {
            $result['infos'][] = ( $object->info() ) ;
        }

        return $result;
        
    }

    public function upload($file, $ext){

        $client         = new Google_Client();
        $storageService = new Google_Service_Storage($client );
        $data           = array('success'=>true);
        $bucket			= $this->bucketName;
        $file_name		= md5(uniqid(rand(), true)) . '.' . $ext;
        $file_content   = urldecode($file);

        try{
            $postbody = array(
                'name' => $file_name,
                'data' => (file_get_contents($file_content)),
                'uploadType' => "media",
                'predefinedAcl' => 'publicRead',
            );

            $gsso = new Google_Service_Storage_StorageObject();
            $gsso->setName( $file_name );
            
            $data['infos'] = $storageService->objects->insert( $bucket, $gsso, $postbody );

        }catch ( Exception $e){
            $result['error'] = json_decode($e->getMessage());
            $result['success'] = false;
        }


        return $result;
    }

    public function delete($id){

        $client = new Google_Client();
        $storageService = new Google_Service_Storage($client );
        $bucket			= $this->bucketName;
        $data           = array('success' => true);

        try{

            $data['data'] = $storageService->objects->delete($bucket,$id);

        }catch (Exception $e){
            $data['success'] = false;
            $data['message'] = json_decode($e->getMessage());

        }

        return $data;

    }
}
