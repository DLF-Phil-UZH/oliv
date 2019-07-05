<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {

	public function up() {		
		/* create table documents */
		$this->dbforge->add_field('id');
		// $this->dbforge->add_key('id', true);
		$docFields = array(
				'aaiId' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'username' => array('type' => 'VARCHAR', 'constraint' => '100'),		
				'firstName' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'lastName' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'lastLogin' => array('type' => 'TIMESTAMP'),	
				'created' => array('type' => 'TIMESTAMP'),
			);
		$this->dbforge->add_field($docFields);
		$this->dbforge->create_table('oliv_users', true);
	
		/* get tablenames with prefixes */
		$usersTableWPrfx = $this->db->dbprefix('oliv_users'); // get the tableName with prefix

		/* change engines to InnoDB and add constraints */
		$this->db->query("ALTER TABLE `$usersTableWPrfx` ENGINE=InnoDB, ADD CONSTRAINT UNIQUE (`username`, `aaiId`)");
	}

	public function down() {
		$this->dbforge->drop_table('oliv_users');
	}	
}

