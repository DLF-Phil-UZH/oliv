<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Person_model {

    private $_aaiId;
    private $_role = 'user';

    public function __construct($pAaiId, $pFirstname, $pLastname, $pTitle = "", $pEmail = "", $pGender = true) {
		parent::__construct($pFirstname, $pLastname, $pTitle, $pEmail, $pGender);
		$this->_aaiId = $pAaiId;
    }

    /* getters: */
	/**
	 *
	 */
    public function getAaiId() {
        return $this->_aaiId;
    }

    public function getRole() {
        return $this->_role;
    }

    public function isAdmin() {
        return ($this->_role == 'admin');
    }


    /* setters: */

    public function setAaiId( $pAaiId ) {
        if ( is_string($pAaiId) ) {
            $this->_aaiId = $pAaiId;
        } else {
            throw new InvalidArgumentException('string expected');
        }
        return $this;
    }

    public function setRole($pRole) {
        if ( is_string($pRole) ) {
            $this->_role = $pRole;
        } else {
            throw new InvalidArgumentException('string expected');
        }
        return $this;
    }
}

