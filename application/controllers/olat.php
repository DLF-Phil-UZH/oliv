<?php

// Based on a CI-Tutorial: http://ellislab.com/codeigniter/user-guide/tutorial/news_section.html
class Olat extends CI_Controller {
	
	private $table_documentLists = "oliv_documentLists"; // Name of database table
	private $table_documents = "oliv_documents"; // Name of database table
	private $table_documents_documentLists = "oliv_documents_documentLists"; // Name of database table
	
	public function __construct() {
		parent::__construct();

        // check login:
        $username = $this->config->item('api_username');
        if ( !isset($_SERVER['REMOTE_USER'])
            || ($_SERVER['REMOTE_USER'] != $username)) {
			    show_error('Sie sind nicht berechtigt.', 401);
        }
	}

	public function lists($pId) {
		$this->output->set_header("Content-Type: text/html; charset=utf-8");

		$this->load->model('document_list_mapper');
		$lDocumentList = $this->document_list_mapper->get($pId);
        if($lDocumentList === false) {
            show_404();
        }
		if($lDocumentList->getPublished()){
			$this->load->view('document_list_header', array("documentList" => $lDocumentList));
            $this->load->view('document_list', array("documentList" => $lDocumentList));
			$this->load->view('document_list_footer', array("documentList" => $lDocumentList));
		}
		else{
			show_404();
		}
	}
	
    public function files($pListHashedId, $pDocumentHashedId) {
        // check if $pDocumentId is in the list:
        $this->load->database();
		
		$lQuery = $this->db->get_where($this->table_documents, array("hashedId" => $pDocumentHashedId));
		$pRow = $lQuery->row();
		$pDocumentId = $pRow->id;
		
		$lQuery = $this->db->get_where($this->table_documentLists, array("hashedId" => $pListHashedId));
		$pRow = $lQuery->row();
		$pListId = $pRow->id;
        
		$where =  array('documentListId' => $pListId,
            'documentId' => $pDocumentId);
        $query = $this->db->get_where($this->table_documents_documentLists, $where);
        if ($query->num_rows() == 1) {
            $this->load->model('document_mapper');
            $lDocument = $this->document_mapper->get($pDocumentId);

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
                // no file found -> display 404 error page
                //show_404();
            }
        } else {
            show_error('403');
        } 
    }

} 

