<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('user_model.php');

/**
 * @author Thomas Bernhart, thomascbernhart@gmail.com
 */
class User_mapper extends CI_Model {

    /**
     * Name of the db table
     * @type string
     */

	private $documentsToAdminsTable = "oliv_documents_admins";
	private $documentListsToAdminsTable = "oliv_documentLists_admins";
	private $table_users = "oliv_users"; // Name of database table
	private $table_user_requests = "oliv_user_requests"; // Name of database table

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }   

	public function save(User_model $pUser){
		$lData = array(
					"aaiId" => $pUser->getAaiId(),
					"firstname" => $pUser->getFirstname(),
					"lastname" => $pUser->getLastname(),
					"email" => $pUser->getEmail(),
					"role" => $pUser->getRole()
				);
		if($pUser->isNew()){
			$lData["created"] = NULL;
			$this->db->insert($this->table_users, $lData);
			$pUser->setId($this->db->insert_id()); // Add id generated by database to the doc list object
		}
		else{
			$this->db->where("id", $pUser->getId());
			$this->db->update($this->table_users, $lData);
		}
	}		

	public function delete(User_model $pUser){
		if (!$User->isNew()) {
			$this->db->where('id', $pUser->getId());
			$this->db->delete($this->table_users);
		}
	}
	
	public function get($pId){
		$lQuery = $this->db->get_where($this->table_users, array('id' => $pId), 1);
		if ($lQuery->num_rows() == 1) {
			$lRow = $lQuery->row();
			$lUser = $this->_createUser($lRow);
			return $lUser;
		}
		return false;
	}
	
	/**
	 *
	 */
	public function getByAaiId($pAaiId) {
    	$lQuery = $this->db->get_where($this->table_users, array('aaiId' => $pAaiId), 1);
		if ($lQuery->num_rows() == 1) {
			$lRow = $lQuery->row();
			$lUser = $this->_createUser($lRow);
			return $lUser;
		}
		return false;
	}

    public function getByDocumentId($pDocumentId) {
        $this->db->select('*');
		$this->db->from($this->table_users);
		$this->db->join($this->documentsToAdminsTable, $this->table_users . '.id = ' . $this->documentsToAdminsTable . '.documentId', 'left');
		$this->db->where('documentId', $pDocumentId);
		// Execute query on database
		$lQuery = $this->db->get();
		// Create document array
        $lAdmins = array();
		foreach($lQuery->result() as $lRow){
			array_push($lAdmins, $this->_createUser($lRow));
		}
		return $lAdmins;
    }

    public function getByDocumentListId($pDocumentListId) {
        $this->db->select('*');
		$this->db->from($this->table_users);
		$this->db->join($this->documentListsToAdminsTable, $this->table_users . '.id = ' . $this->documentListsToAdminsTable . '.documentListId', 'left');
		$this->db->where('documentListId', $pDocumentListId);
		// Execute query on database
		$lQuery = $this->db->get();
		// Create document array
        $lAdmins = array();
		foreach($lQuery->result() as $lRow){
			array_push($lAdmins, $this->_createUser($lRow));
		}
		return $lAdmins;
    }


    /**
     *
     */
    public function create_user_from_request($p_user_request_id) {
        // sql:
        // INSERT INTO `oliv_users` (SELECT null, `aaiId`, `firstname`, `lastname`, `email`, 'user', null, null FROM `oliv_user_requests` WHERE `oliv_user_request`.`id` = :id);
        // DELETE FROM `oliv_user_requests` WHERE `oliv_user_requests`.`id` = :id; 
        $l_users_table = $this->db->dbprefix($this->table_users);
        $l_user_requests_table = $this->db->dbprefix($this->table_user_requests);
        $sql_insert = "INSERT IGNORE INTO `$l_users_table`";
        $sql_insert .= " (SELECT null, `aaiId`, `firstname`, `lastname`, `email`, 'user', null, null";
        $sql_insert .= " FROM `$l_user_requests_table` WHERE `$l_user_requests_table`.`id` = ";
        $sql_insert .= $this->db->escape($p_user_request_id). ');';
        $sql_delete = "DELETE FROM `$l_user_requests_table` WHERE `id` = " . $this->db->escape($p_user_request_id) . ';';
        
        $this->db->trans_start();
        $this->db->query($sql_insert);
        $this->db->query($sql_delete); 
        $this->db->trans_complete();
        $this->db->trans_off();

        if ($this->db->trans_status() === FALSE) {
            // TODO: generate error message
            return false;
        }
        return true;
    }
	
	public function setActiveTimestamp($pUserId){
		$this->db->where("id", $pUserId);
		$this->db->set("lastLogin", "NOW()", FALSE);
		$this->db->update($this->table_users);
	}

	/**
	 * TODO
	 */
	/*
	private function _get($pParams = array(), $pLimit) {
    	if (is_array($pParams)) {
			$this->db->where($pParams);
		} else {
			$this->db->where('id', $pId);
			$this->db->where->limit(1);
		}
		if ($lquery->num_rows() == 1) {
			
		}

	}
	*/

	/**
 	 *
 	 */
	private function _createUser($pRow) {
    	$lUser = new User_model($pRow->aaiId, $pRow->firstname, $pRow->lastname, $pRow->email);
		$lUser->setId($pRow->id);
		$lUser->setRole($pRow->role);
		return $lUser;
	}
}


/* End of file user_mapper.php */
/* Location: ./application/library/user_mapper.php */

