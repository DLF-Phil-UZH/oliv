<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	private $table_user_requests = "oliv_user_requests"; // Name of database table

	public function __construct() {
		parent::__construct();
		$this->load->library('shibboleth_authentication_service', NULL, 'shib_auth');
	}

	/**
     * 
	 */
	public function index(){
        if ($user = $this->shib_auth->verify_user() !== false) {
			// if there is a user and he has access, redirect to
			// the manager page
			redirect('welcome');
		}

		// If user has already logged in over AAI
		if($this->shib_auth->verify_shibboleth_session()){
			$this->load->view('header', array('title' => 'Oliv: Zugang beantragen',
										  'page' => 'request_access',
										  'width' => 'small',
                                          'logged_in' => $this->shib_auth->verify_shibboleth_session(),
										  'access' => ($this->shib_auth->verify_user() !== false)));
			$this->load->view('request_access');
		}
		// If user hasn't logged in over AAI yet, send him to login page
		else{
			$this->load->view('header', array('title' => 'Oliv: Authentifizierung',
										  'page' => 'authentification',
										  'width' => 'small',
                                          'logged_in' => false,
										  'access' => false));
            $return_url = site_url('/manager/documents');
			$this->load->view('login', array('return_url' => $return_url, 'user' => $this->shib_auth->get_server_variable()));
		}
		$this->load->view('footer');
	}

	/**
	 *
	 */
    public function request_access() {
        if ($user = $this->shib_auth->verify_user() !== false) {
			// if there is a user and he has access, redirect to
			// the manager page
			redirect('welcome');
		}

		if (!$this->shib_auth->verify_shibboleth_session()) {
			redirect('auth');
		}
		$this->load->database();
		$lAaiId = $this->shib_auth->get_unique_user_id();
        if ($lAaiId === false) {
            throw new Exception('No shibboleth uniqueID specified.');
        }
		// check if request already exists
		$lQuery = $this->db->get_where($this->table_user_requests, array("aaiId" => $lAaiId), 1);
		if ($lQuery->num_rows() > 0) {
			$lRow = $lQuery->row();
			// this user has already requested access
			// tell him that
			$lRequestedTimestampString = $lRow->created;
			$lRequestedTimestamp = new DateTime(date("Y-m-d H:i:s", strtotime($lRequestedTimestampString)));
			$lRequestDate = date_format($lRequestedTimestamp, 'd.m.Y');
			$lRequestTime = date_format($lRequestedTimestamp, 'H:i:s');
		} else {
			// ok make an access request entry in the db
			$lFirstname = $_SERVER['givenName'];
			$lLastname = $_SERVER['surname'];
			$lEmail = $_SERVER['mail'];

			$lData = array(
						'aaiId' => $lAaiId,
						'firstname' => $lFirstname,
						'lastname' => $lLastname,
						'email' => $lEmail
						); 
			$this->db->insert($this->table_user_requests, $lData);
			$lRequestedTimestamp = new DateTime();
			$lRequestDate = date_format($lRequestedTimestamp, 'd.m.Y');
			$lRequestTime = date_format($lRequestedTimestamp, 'H:i:s');
		}
		
		// send message to inform about user access requests
		$message = "$lFirstname $lLastname mit der AaiId $lAaiId und der Emailadresse $lEmail hat soeben einen Zugang zu Oliv beantragt.";
		$message = wordwrap($message, 70);
		$to = '';
		$subject = 'Oliv-Zugang beantragt';
		$header = 'From: ' . "\r\n" . 'Reply-To: ' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $header);
				
		$this->load->view('header', array('title' => 'Oliv: Zugang beantragt',
										  'page' => 'access_requested',
										  'width' => 'small',
                                          'logged_in' => $this->shib_auth->verify_shibboleth_session(),
										  'access' => ($this->shib_auth->verify_user() !== false)));
		$this->load->view('access_requested', array('request_date' => $lRequestDate,
													'request_time' => $lRequestTime));
		$this->load->view('footer');
	}

	/**
	 *
	 */
    public function logout() {
        if (!$this->shib_auth->verify_shibboleth_session()) {
            redirect('auth');
            return;
		}

        $user = $this->shib_auth->verify_user();
        if ($user != false) {
            // TODO: clean up locks
            // $this->shib_auth->log_out();
            $user_id = $user->getId();

            $this->load->library('Grocery_CRUD');
            $grocery_crud = new Grocery_CRUD($user);
            $success = $grocery_crud->unlock_all_records();
            if (!$success) {
                // TODO: log some error message
            }
        }

        // TODO: Display, login link / hide logout link in Header
        $this->load->view('header', array('title' => 'Oliv: Abgemeldet',
                                      'page' => 'logout',
                                      'width' => 'small',
                                      'logged_in' => false,
                                      'access' => false));
        $this->load->view('logout');
        $this->load->view('footer');

    }
	
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */

