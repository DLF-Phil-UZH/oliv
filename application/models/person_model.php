<?php

class Person_model extends Abstract_base_model{
	
	public $firstname;
	public $lastname;
	public $title;
	// public $email;
	public $gender; // m <=> true; w <=> false
	
	// Constructor
	function __construct($pFirstname, $pLastname, $pTitle = "", /* $pEmail = "",$*/ $pGender = true){
		$this->firstname = $pFirstname;
		$this->lastname = $pLastname;
		$this->title = $pTitle;
		// $this->email = $pEmail;
		$this->gender = $pGender;
	}
	
	// Setters
	
	public function setFirstname($pFirstname){
		$this->firstname = $pFirstname;
	}
	
	public function setLastname($pLastname){
		$this->lastname = $pLastname;
	}

	public function getFullName() {
        return $this->_firstname . " " . $this->_lastname;
    }

	
	public function setTitle($pTitle){
		$this->title = $pTitle;
	}
	
	public function setGender($pGender){
		$this->gender = $pGender;
	}
	
	// Getters
	
	public function getFirstname(){
		return $this->firstname;
	}
	
	public function getLastname(){
		return $this->lastname;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getGender(){
		return $this->gender;
	}
	
	// Other functions
	
	public function iterateVisible(){
		// FIXME: Never do echo in models!
		echo "Person::iterateVisible:\n";
		foreach($this as $key => $value){
			print "$key => $value\n";
		}
	}
}

