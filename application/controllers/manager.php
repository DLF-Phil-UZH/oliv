<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends CI_Controller {

    private $user = false;
    private $adminaccess = false;

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('Shibboleth_authentication_service', NULL, 'shib_auth');
		$this->user = $this->shib_auth->verify_user();
		if ($this->user == false) {
			redirect('auth');
		} 
		if ($this->user !== false && $this->user->getRole() == 'new') {
			show_error('Sie haben keine Berechtigung.', 403);
		}

		// Check if user is admin for displaying navigation
		$this->adminaccess = $this->user->isAdmin();
		
		// Set timestamp of last login to now (meant as last access to main site)
		$this->load->model('User_mapper');
		$this->User_mapper->setActiveTimestamp($this->user->getId());
		log_message('debug', "User " . $this->user->getId() . " is active");
	}
	
	/**
	 * Renders CRUD output on crud view.
	 * 
	 * @param	string	$pPage		Name of displayed page ("lists" or "documents")
	 * @param			$pOutput	CRUD output
	 * @access	private
	 */
	private function _render_output($pPage, $pOutput = null){
		$this->load->view('header', array('title' => 'Oliv',
										  'page' => $pPage,
										  'width' => 'normal',
                                          'logged_in' => $this->shib_auth->verify_shibboleth_session(),
										  'access' => ($this->shib_auth->verify_user() !== false),
										  'admin' => $this->adminaccess));
		$this->load->view('crud', $pOutput);
		$this->load->view('footer');
	}

			
	public function index()
	{
        redirect(site_url('/manager/documents'));
	}
	
    /**
     * Display a CRUD table for Document_models
     */
	public function documents()
	{
        $this->load->library('crud_service');
		try{
			$crudOutput = $this->crud_service->getDocumentsCrud();
			$this->_render_output("documents", $crudOutput);
		}catch(Exception $e){
            $this->_handle_crud_exception(e);	
		}
    }

	/**
	 * Send the requested file.
	 */
	public function documents_file($pId) {
		$this->load->model('document_mapper');
		$lDocument = $this->document_mapper->get($pId);
		if ($lDocument != false) {
		    $l_file = $lDocument->getFilePath();
            $l_documentname = $lDocument->getExplicitId() . '.pdf';
        }

		if (isset($l_file) && file_exists($l_file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename='.$l_documentname);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($l_file));
			ob_clean();
			flush();
			readfile($l_file);
			exit;
		} else {
            show_404();
        }
    }

   	/**
	 *
	 */
    public function documents_file_upload($pId) {
        if (!$this->_document_is_locked_for_edit($pId)) {
            $this->output->set_status_header(403);        
            return;
        }
		$this->load->model('document_mapper');
		$lDocument = $this->document_mapper->get($pId);
		if (!$lDocument) {
            // TODO: return some error message
            $this->output->set_status_header(500);
            return;
        }
        // no longer needed, as settings are loaded automatically from upload.php
        // $this->config->load('pdf_upload', TRUE);
        // $global_upload_config = $this->config->item('pdf_upload');
        // $upload_config = $global_upload_config;
        // $upload_config['file_name'] = uniqid();

        // $this->load->library('upload', $upload_config);
        $this->load->library('upload');

        // TODO: test if filetype is pdf
		$lUploadStatus = $this->upload->do_upload();
		if (!$lUploadStatus) {
            // return error message from upload library
            $errors_string = $this->upload->display_errors('', '//');
            $errors = explode('//', $errors_string);
            $this->output
                ->set_status_header('500')
                ->set_content_type('application/json')
                ->set_output(json_encode(array('errors' => $errors)));

			return;
		}
		$file_data = $this->upload->data();
		// set the fileName in the Document_model:
		$lDocument->setFileName($file_data['file_name']);
		$this->document_mapper->save($lDocument);
 
        // TODO: return a JSON
        $json_data = array(
                        "files" => array(
                            "name" => $file_data['file_name'],
                            "size" => $file_data['file_size'],
                            "url" => base_url('files/') . $file_data['file_name'],
                            "delete_url" => base_url('files/delete/'.$file_data['file_name']),
                            "delete_type" => "DELETE"
                            )
                        );
       $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($json_data));
    }

    /**
     * Delete the PDF file associated with the Document_model.
     */
    public function documents_file_delete($pId) {
        if (!$this->_document_is_locked_for_edit($pId)) {
            $this->output->set_status_header(403);        
            return;
        }
        $this->load->model('document_mapper');
		$lDocument = $this->document_mapper->get($pId);
		if (!$lDocument) {
            // TODO: return some error message
            $this->output->set_status_header(500);
            return;
        }
        $filePath = $lDocument->getFilePath();
        if (file_exists($filePath)) {
            // TODO: check if file really has been unlinked.
            $lDocument->setFileName('');
            // TODO: check if $lDocument really has been saved.
            $this->document_mapper->save($lDocument);
            $status = unlink($filePath);
            $this->output->set_status_header(200);
        } else {
            $this->output->set_status_header(404);
        }
    }


	public function lists()
	{
		$this->load->library('crud_service');			
		try {
			$crudOutput = $this->crud_service->getDocumentListsCrud();
			$this->_render_output("lists", $crudOutput);
		} catch(Exception $e) {
		    $this->_handle_crud_exception(e);	
		}
	}

    /**
     * Publish a list, so that it is accessible via OLAT.
     */
    public function publish_list($pId) {
        $this->load->model('document_list_mapper');
        $lDocumentList = $this->document_list_mapper->get($pId);
        // $lDocumentList = false;
        $status = false;
        if ($lDocumentList == false) {
             // TODO: return some error message
            show_404(); 
        } else {
            $lDocumentList->setPublished(true);
            $this->document_list_mapper->save($lDocumentList);
            redirect('manager/lists/success/' . $pId);
        }
    }

    private function _get_user() {
        return $this->user;
    }

    private function _handle_crud_exception(Exception $e) {
        if (e.getCode() == 14) {
                show_error('Sie haben keine Berechtigung.', 403);
        } else {
	        show_error($e->getMessage().' --- '.$e->getTraceAsString());

        }
    }

    private function _document_is_locked_for_edit($primary_key) {
        // copy of the function Grocery_CRUD::record_is_locked_for_edit
        // TODO: put this function in a separate class
        $user = $this->_get_user();
        if (!$user) {
            // throw exception?
            throw new Exception('No user specified.');
        }
        // load db:
        $this->load->database();
        $db = $this->db;        
        $lock_tablename = 'oliv_groceryCrudLocks';
        $tablename = 'oliv_documents';
        $query = $db->get_where($lock_tablename, array('tablename' => $tablename, 'recordId' => $primary_key));
        if ($query->num_rows() > 1) {
            // Throw exception!
        }
        $row = $query->row();
        $current_user_id = $row->userId;
        $edit_timestamp = new DateTime($row->timestamp);
        $current_timestamp = new DateTime(); 
        $time_difference = ($current_timestamp->format("U") - $edit_timestamp->format("U"));

        if ($current_user_id == $user->getId() && $time_difference <= 3600) {
            return true;
        }
        return false;
    }

}

