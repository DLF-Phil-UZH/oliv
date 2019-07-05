<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_Db_Tables extends CI_Migration {

	private $table_documents = "oliv_documents"; // Name of database table
	private $table_documentLists = "oliv_documentLists"; // Name of database table
	private $table_documents_documentLists = "oliv_documents_documentLists"; // Name of database table

	public function up() {
		/* create table oliv_documents */
		$this->dbforge->add_field('id');
		// $this->dbforge->add_key('id', true);
		$docFields = array(
				'explicitId' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'fileName' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'title' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'authors' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'publication' => array('type' => 'VARCHAR', 'constraint' => '200'),
				'editors' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'publishingHouseAndPlace' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'year' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'place' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'pages' => array('type' => 'VARCHAR', 'constraint' => '100'),
				'creator' => array('type' => 'INT', 'constraint' => '9'),
				'admin' => array('type' => 'INT', 'constraint' => '9'),
				// lastUpdated has to come before created,
				// so that the "on update CURRENT_TIMESTAMP" constraint is used here
				'lastUpdated' => array('type' => 'TIMESTAMP'),	
				'created' => array('type' => 'TIMESTAMP'),
			);
		$this->dbforge->add_field($docFields);
		$this->dbforge->create_table($this->table_documents, true);
	
		/* create table oliv_documentLists */
		$this->dbforge->add_field('id');
		// $this->dbforge->add_key('id', true);
		$listFields = array(
				'title' => array('type' => 'VARCHAR', 'constraint' => '255'),
				'creator' => array('type' => 'INT', 'constraint' => '9'),
				'admin' => array('type' => 'INT', 'constraint' => '9'),
				// lastUpdated has to come before created,
				// so that the "on update CURRENT_TIMESTAMP" constraint is used here
				'lastUpdated' => array('type' => 'TIMESTAMP'),
				'created' => array('type' => 'TIMESTAMP')
			);
		$this->dbforge->add_field($listFields);
		$this->dbforge->create_table($this->table_documentLists, true);

		/* create table oliv_documents_documentLists */
		$doc2listFields = array(
				'documentId' => array('type' => 'INT', 'constraint' => '9'),
				'documentListId' => array('type' => 'INT', 'constraint' => '9'),
				'lastUpdated' => array('type' => 'TIMESTAMP')
			);
		$this->dbforge->add_field($doc2listFields);
		$this->dbforge->create_table($this->table_documents_documentLists, true);

		/* get tablenames with prefixes */
		$docsTableWPrfx = $this->db->dbprefix($this->table_documents); // get the tableName with prefix
		$listsTableWPrfx = $this->db->dbprefix($this->table_documentLists); // get the tableName with prefix
		$doc2listTableWPrfx = $this->db->dbprefix($this->table_documents_documentLists); // get the tableName with prefix

		/* change engines to InnoDB and add constraints */
		$this->db->query("ALTER TABLE `$docsTableWPrfx` ENGINE=InnoDB, ADD CONSTRAINT UNIQUE (`explicitId`)");
		$this->db->query("ALTER TABLE `$listsTableWPrfx` ENGINE=InnoDB");
		$this->db->query("ALTER TABLE `$doc2listTableWPrfx` ENGINE=InnoDB, ADD PRIMARY KEY (`documentId`, `documentListId`)");
		$this->db->query("ALTER TABLE `$doc2listTableWPrfx` ADD CONSTRAINT FOREIGN KEY (`documentId`) REFERENCES `$docsTableWPrfx` (`id`) ON UPDATE CASCADE ON DELETE CASCADE");
		$this->db->query("ALTER TABLE `$doc2listTableWPrfx` ADD CONSTRAINT FOREIGN KEY (`documentListId`) REFERENCES `$listsTableWPrfx` (`id`) ON UPDATE CASCADE ON DELETE CASCADE");
	}

	public function down() {
		$this->dbforge->drop_table($this->table_documents);
		$this->dbforge->drop_table($this->table_documentLists);
		$this->dbforge->drop_table('documents2lists');
	}	
}

