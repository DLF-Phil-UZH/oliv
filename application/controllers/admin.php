<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('shibboleth_authentication_service', NULL, 'shib_auth');
		$user = $this->shib_auth->verify_user();
		if ($user == false) {
			redirect('auth');
   		} elseif ($user->isAdmin() === false) {
			$this->adminaccess = false;
        } else {
			$this->adminaccess = true;
		}
		$this->load->database();
	}

	public function index(){
	    redirect(site_url('/admin/users'));	
	}

	public function users(){
		if($this->adminaccess === true){
			$this->load->library('Crud_service');
			try{
				$crudOutput = $this->crud_service->getUsersCrud();
				$this->load->view('header', array('title' => 'Oliv: Administration',
											  'page' => 'users',
											  'width' => 'normal',
                                              'logged_in' => $this->shib_auth->verify_shibboleth_session(),
											  'access' => ($this->shib_auth->verify_user() !== false),
											  'admin' => $this->adminaccess));
				$this->load->view('crud.php',$crudOutput);
				$this->load->view('footer');
			}catch(Exception $e){
				$this->_handle_crud_exception($e);
			}
		}
		// If user is not an admin
		else{
			$this->_access_denied();
		}
	}

	public function user_requests(){
		if($this->adminaccess === true){
			$this->load->library('Crud_service');
			try{
				$crudOutput = $this->crud_service->getUserRequestsCrud();
				$this->load->view('header', array('title' => 'Oliv: Administration',
											  'page' => 'user_requests',
                                              'width' => 'normal',
                                              'logged_in' => $this->shib_auth->verify_shibboleth_session(),
											  'access' => ($this->shib_auth->verify_user() !== false),
											  'admin' => $this->adminaccess));
				$this->load->view('crud.php',$crudOutput);
				$this->load->view('footer');
			}catch(Exception $e){
				$this->_handle_crud_exception($e);
			}
		}
		// If user is not an admin
		else{
			$this->_access_denied();
		}
	}
	
	/**
	 *
	 */
	public function accept_user_request($pId) {
		// create a user account for the specified id:
        $this->load->model('user_mapper');
        $status = $this->user_mapper->create_user_from_request($pId);
        // redirect to user_requests admin interface
        redirect(site_url() . 'admin/user_requests');
	}

    private function _access_denied() {
        $this->output->set_status_header('403');
		$this->load->view('header', array('title' => 'Oliv: Zugriff verweigert',
										  'page' => 'access_denied',
										  'width' => 'small',
                                          'logged_in' => $this->shib_auth->verify_shibboleth_session(),
										  'access' => ($this->shib_auth->verify_user() !== false)));
		$this->load->view('access_denied');
		$this->load->view('footer');
	}

    private function _handle_crud_exception(Exception $e) {
        if (e.getCode() == 14) {
            $this->_access_denied(); 
        } else {
	        show_error($e->getMessage().' --- '.$e->getTraceAsString());

        }
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
