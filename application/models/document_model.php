<?php

class Document_model extends Abstract_base_model{

	private $hashedId; // MD5 Hash of ID
	private $explicitId; // Combined document ID
	private $fileName; // Name of document file
    private $fileDirPath;
	private $title; // Title (chapter or article)
	private $authors; // Authors of document
	private $publication; // Title of publication
	private $volume; // Issue of magazine or volume of publication
	private $editors; // Editors of publication
	private $places; // Place of publishing house
	private $publishingHouse; // Name of publishing house
	private $year; // Year of publication, optionally with edition in superior (<sup> element in HTML)
	private $pages; // Start page in the book/magazine
	private $webURL; // URL of PDF if read or downloaded from the internet
	private $webAccess; // Access Date when read or downloaded from the internet
	// Creator and admin are temporarily only represented as foreign keys (person ID) in the Document_list_model object
	// Might be changed later to entire Person_model objects
	private $creator; // Creator
	private $admins = array(); // Admin
	private $lastUpdated; // Date and time of last update (type DateTime)
    private $created; // Date and time of creation (type DateTime)
    private $citation_style; // Citation style
	
	public function __construct($pHashedId, $pExplicitId, $pFileName, $pTitle, $pAuthors, $pPublication, $pVolume, $pEditors, $pPlaces, $pPublishingHouse, $pYear, $pPages, $pURL, $pWebAccess, $pCreator){
		$this->hashedId = $pHashedId;
		$this->explicitId = $pExplicitId;
		$this->fileName = $pFileName;
		$this->title = $pTitle;
		$this->authors = $pAuthors;
		$this->publication = $pPublication;
		$this->volume = $pVolume;
		$this->editors = $pEditors;
		$this->places = $pPlaces;
		$this->publishingHouse = $pPublishingHouse;
		$this->year = $pYear;
		$this->pages = $pPages;
		$this->webURL = $pURL;
		$this->webAccess = $pWebAccess;
		$this->creator = $pCreator;

        $ci = &get_instance();
        $this->fileDirPath = $ci->config->item('pdf_folder');
        $this->citation_style = $ci->config->item('citation_style');
	}
	
	// Setters
	
	public function setId($pId){
		$this->id = $pId;
	}
	
	public function setExplicitId($pExplicitId){
		$this->explicitId = $pExplicitId;
	}
	
	public function setFileName($pFileName){
		$this->fileName = $pFileName;
	}
	
	public function setTitle($pTitle){
		$this->title = $pTitle;
	}
	
	public function setAuthors($pAuthors){
		$this->authors = $pAuthors;
	}
	
	public function setPublication($pPublication){
		$this->publication = $pPublication;
	}
	
	public function setVolume($pVolume){
		$this->volume = $pVolume;
	}
	
	public function setEditors($pEditors){
		$this->editors = $pEditors;
	}
	
	public function setPlaces($pPlaces){
		$this->places = $pPlaces;
	}
	
	public function setPublishingHouse($pPublishingHouse){
		$this->publishingHouse = $pPublishingHouse;
	}
	
	public function setYear($pYear){
		$this->year = $pYear;
	}
	
	public function setPages($pPages){
		$this->pages = $pPages;
	}
	
	public function setURL($pURL){
		$this->webURL = $pURL;
	}
	
	public function setWebAccess($pWebAccess){
		$this->webAccess = $pWebAccess;
	}
	
	public function setCreator($pCreator){
		$this->creator = $pCreator;
	}
	
	public function setLastUpdated(DateTime $pLastUpdated){
		$this->lastUpdated = $pLastUpdated;
	}
	
	public function setCreated(DateTime $pCreated){
		$this->created = $pCreated;
	}
	
	// Getters
	
	public function getId(){
		return $this->id;
	}
	
	public function getHashedId(){
		return $this->hashedId;
	}
	
	public function getExplicitId(){
		return $this->explicitId;
	}
	
	public function getFileName(){
		return $this->fileName;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getAuthors(){
		return $this->authors;
	}
	
	public function getPublication(){
		return $this->publication;
	}
	
	public function getVolume(){
		return $this->volume;
	}
	
	public function getEditors(){
		return $this->editors;
	}
	
	public function getPlaces(){
		return $this->places;
	}
	
	public function getPublishingHouse(){
		return $this->publishingHouse;
	}
	
	public function getYear(){
		return $this->year;
	}
	
	public function getPages(){
		return $this->pages;
	}
	
	public function getURL(){
		return $this->webURL;
	}
		
	public function getWebAccess(){
		return $this->webAccess;
	}
	
	public function getCreator(){
		return $this->creator;
	}
	
    // public function getAdmins(){
    //     $admins = $this->admins;
	// 	return $admins;
	// }

    // public function getAdminById($pId) {
    //     if(array_key_exists($pId, $this->admins)){
	// 		return = $this->admins[$pId]);
	// 	}
    //     return false;
    // }

	
	public function getLastUpdated(){
		return $this->lastUpdated;
	}
	
	public function getCreated(){
		return $this->created;
	}
	
	// Other methods
	
	// Returns true, if document has not been registered in database so far
	public function isNew(){
		$lIsNew = false;
		if($this->id == null){
			$lIsNew = true;
		}
		return $lIsNew;
	}

    //public function addAdmin($pUser) {
    //    $lId = $pUser->getId();
	//	if(!array_key_exists($lId, $this->admins)){
	//		$this->admins[$lId] = $pDocument; // Entry: Id as key of array, id as value at key of array
	//	}
    //}
    //
    //public function removeAdminById($pId) {
    //    if(array_key_exists($pId, $this->admins)){
	//		unset($this->admins[$pId]);
	//	}
    //}

    //public function removeAdmin($pUser) {
    //    $lId = $pUser->getId();
	//	if(array_key_exists($lId, $this->admins)){
	//		unset($this->admins[$lId]);
	//	}
    //}
	
	/*
	Creates an explicit ID (as a proposal, does not write it to attribute!) based on authors and year
	Format:
	<Lastname_of_first_author>[_<Lastname_of_next_author>]*_<year>
	Example:
	"Koch_2003"
	*/
	public function createExplicitId(){
		// Add lastname of author(s)
		$lAuthors = $this->authors;
		if(strpos($lAuthors, ",") !== false){
			$lExplicitId = substr($lAuthors, 0, strpos($lAuthors, ",")) . "_"; // Add lastname of first author
		}
		while(strpos($this->authors, "/") !== false){
			$lAuthors = substr($lAuthors, strpos($lAuthors, "/")); // Delete preceding author
			$lAuthors = ltrim($lAuthors, "/ "); // Delete beginning slash(es) and space(s)
			$lExplicitId = substr($lAuthors, 0, strpos($lAuthors, ",")) . "_"; // Add lastname of next author
		}
		
		// Add year
		$lYear = $this->year;
		// Extract first publication, if given
		if(strpos($lYear, "[") !== false && strpos($lYear, "]") !== false){
			$lYear = substr($lYear, strpos($lYear, "[") + 1, strpos($lYear, "]"));
		}
		// Delete edition number, if given
		if(strpos($lYear, "<sup>") !== false && strpos($lYear, "</sup>") !== false){
			$lYear = substr($lYear, strpos($lYear, "</sup>"));
		}
		$lExplicitId = $lExplicitId . $lYear;
		
		return $lExplicitId;
    }

	// Returns formatted HTML string according to specifications on citation style submitted in an e-mail by A. Robert-Tissot, 10.06.2013 or the official APA / MLA standard
	public function toFormattedString(){

        switch ($this->citation_style) {

            case 'MLA':
                $lFormattedString = $this->_toFormattedStringMLA();
                break;
            
            case 'APA':
				$lFormattedString = $this->_toFormattedStringAPA();
                break;

            case 'ROSE':
                $lFormattedString = $this->_toFormattedStringROSE();
                break;

            default:
                throw new Exception("Unexpected configuration option citation_style = '" + $style + "'.");
        }    

        return $lFormattedString;
	}
	
	public function getFilePath(){
		if(file_exists($this->fileDirPath . $this->fileName)){
			return $this->fileDirPath . $this->fileName;
		}
		else{
			return false;
		}
	}
	
	// Expects "KB" ("KiB"), "MB" ("MiB"), or "GB" ("GiB") as given units and returns the file size in KB (KiB), MB (MiB) or GB (GiB) or in Byte for any other passed argument
	public function getFileSize($pUnit){
		$lFileSize = 0.0;
		if($this->getFilePath() !== false){
			$lFileSize = filesize($this->getFilePath());
			if(strcmp($pUnit, "KB") === 0){
				$lFileSize /= 1000;
			}
			elseif(strcmp($pUnit, "MB") === 0){
				$lFileSize /= (1000 * 1000);
			}
			elseif(strcmp($pUnit, "GB") === 0){
				$lFileSize /= (1000 * 1000 * 1000);
			}
			elseif(strcmp($pUnit, "KiB") === 0){
				$lFileSize /= 1024;
			}
			elseif(strcmp($pUnit, "MiB") === 0){
				$lFileSize /= (1024 * 1024);
			}
			elseif(strcmp($pUnit, "GiB") === 0){
				$lFileSize /= (1024 * 1024 * 1024);
			}
		}
		return $lFileSize;
    }

    private function _toFormattedStringROSE() {
        $lFormattedString = "";
        // If document is a monography without page indication
            if(strlen($this->editors) + strlen($this->pages) + strlen($this->publication) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "): <i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ".";
            }
            // If document is a monography with page indication
            elseif(strlen($this->editors) + strlen($this->publication) == 0 && strlen($this->pages) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "): <i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ", ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is a chapter of a book
            elseif(strlen($this->editors) == 0 && strlen($this->places) > 0 && strlen($this->publishingHouse) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "): \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= "\", in: ";
                $lFormattedString .= $this->authors;
                $lFormattedString .= ": <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ", ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is a magazine article
            elseif(strlen($this->editors) + strlen($this->places) + strlen($this->publishingHouse) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "): \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= "\", in: <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is an article in a book
            elseif(strlen($this->title) != 0 &&
                    strlen($this->authors) != 0 &&
                    strlen($this->publication) != 0 &&
                    strlen($this->editors) != 0 &&
                    strlen($this->places) != 0 &&
                    strlen($this->publishingHouse) != 0 &&
                    strlen($this->pages) != 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "): \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= "\", in: ";
                $lFormattedString .= $this->editors;
                $lFormattedString .= ": <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ", ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document type cannot be determined, use minimal citation
            else{
                if(strlen($this->authors) > 0){
                    $lFormattedString .= $this->authors;
                    $lFormattedString .= ": ";
                }
                $lFormattedString .= $this->title;
                $lFormattedString .= ".";
            }

            return $lFormattedString;
    }
	
	private function _toFormattedStringMLA() {
        $lFormattedString = "";
        // If document is a monography without page indication
            if(strlen($this->editors) + strlen($this->pages) + strlen($this->publication) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". ";
                $lFormattedString .= "<i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
				$lFormattedString .= ", ";
				$lFormattedString .= $this->year;
                $lFormattedString .= ".";
            }
            // If document is a monography with page indication
            elseif(strlen($this->editors) + strlen($this->publication) == 0 && strlen($this->pages) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". ";
                $lFormattedString .= "<i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
				$lFormattedString .= ", ";
				$lFormattedString .= $this->year;
                $lFormattedString .= ". ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is a chapter of a book
            elseif(strlen($this->editors) == 0 && strlen($this->places) > 0 && strlen($this->publishingHouse) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= ".\"";
                $lFormattedString .= " <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ". ";
				$lFormattedString .= $this->authors;
				$lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
				$lFormattedString .= ", ";
				$lFormattedString .= $this->year;
                $lFormattedString .= ". ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is a magazine article
            elseif(strlen($this->editors) + strlen($this->places) + strlen($this->publishingHouse) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= ".\"";
                $lFormattedString .= " <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
				$lFormattedString .= " (";
				$lFormattedString .= $this->year;
				$lFormattedString .= "): ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is an article in a book
            elseif(strlen($this->title) != 0 &&
                    strlen($this->authors) != 0 &&
                    strlen($this->publication) != 0 &&
                    strlen($this->editors) != 0 &&
                    strlen($this->places) != 0 &&
                    strlen($this->publishingHouse) != 0 &&
                    strlen($this->pages) != 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= ".\"";
                $lFormattedString .= " <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " ";
                    $lFormattedString .= $this->volume;
                }
                $lFormattedString .= ". Hrsg. ";
				$lFormattedString .= $this->editors;
				$lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ", ";
				$lFormattedString .= $this->year;
				$lFormattedString .= ". ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
			elseif(strlen($this->webURL) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= ". \"";
                $lFormattedString .= $this->title;
                $lFormattedString .= ".\"";
                $lFormattedString .= " <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                $lFormattedString .= ". ";
				if(strlen($this->editors) > 0){
					$lFormattedString .= $this->editors;
					$lFormattedString .= ", ";
                }
				$lFormattedString .= $this->year;
				$lFormattedString .= ". ";
				$lFormattedString .= "Web. ";				
				$lFormattedString .= $this->webAccess;
				$lFormattedString .= ". <";
				$lFormattedString .= $this->webURL;
				$lFormattedString .= ">.";
            }
            // If document type cannot be determined, use minimal citation
            else{
                if(strlen($this->authors) > 0){
                    $lFormattedString .= $this->authors;
                    $lFormattedString .= ". ";
                }
                $lFormattedString .= "<i>";
				$lFormattedString .= $this->title;
				$lFormattedString .= "</i>.";
            }

            return $lFormattedString;
    }
	
	private function _toFormattedStringAPA() {
        $lFormattedString = "";
		// If document is a website
		if(strlen($this->webURL) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). ";
                $lFormattedString .= $this->title;
                $lFormattedString .= ". ";
				$lFormattedString .= "<i>";
                $lFormattedString .= $this->publication;
				$lFormattedString .= "</i>";
                $lFormattedString .= ". ";
				$lFormattedString .= "Abgerufen am ";				
				$lFormattedString .= $this->webAccess;
				$lFormattedString .= ", ";
				$lFormattedString .= "von ";
				$lFormattedString .= $this->webURL;
				$lFormattedString .= ".";
            }
        // If document is a monography without page indication
           elseif(strlen($this->editors) + strlen($this->pages) + strlen($this->publication) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). <i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " (Bd. ";
                    $lFormattedString .= $this->volume;
					$lFormattedString .= ")";
                }
                $lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ".";
            }
            // If document is a monography with page indication
            elseif(strlen($this->editors) + strlen($this->publication) == 0 && strlen($this->pages) > 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). <i>";
                $lFormattedString .= $this->title;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " (Bd. ";
                    $lFormattedString .= $this->volume;
					$lFormattedString .= ", S. ";
					$lFormattedString .= $this->pages;					
					$lFormattedString .= ")";
                }
				else {
					$lFormattedString .= " (S. ";
					$lFormattedString .= $this->pages;
					$lFormattedString .= ")";
				}
				$lFormattedString .= ". ";				
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ".";
            }
            // If document is a chapter of a book
            elseif(strlen($this->editors) == 0 && strlen($this->places) > 0 && strlen($this->publishingHouse) > 0){
				$lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). ";
                $lFormattedString .= $this->title;
                $lFormattedString .= ". In ";
                $lFormattedString .= $this->authors;
                $lFormattedString .= ", <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " (Bd. ";
                    $lFormattedString .= $this->volume;
					$lFormattedString .= ", S. ";
					$lFormattedString .= $this->pages;					
					$lFormattedString .= ")";
                }
				else {
					$lFormattedString .= " (S. ";
					$lFormattedString .= $this->pages;
					$lFormattedString .= ")";
				}	
                $lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ".";
            }
            // If document is a magazine article
            elseif(strlen($this->editors) + strlen($this->places) + strlen($this->publishingHouse) == 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). ";
                $lFormattedString .= $this->title;
                $lFormattedString .= ". <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= "(";
                    $lFormattedString .= $this->volume;
					$lFormattedString .= ")";
                }
                $lFormattedString .= ", ";
                $lFormattedString .= $this->pages;
                $lFormattedString .= ".";
            }
            // If document is an article in a book
            elseif(strlen($this->title) != 0 &&
                    strlen($this->authors) != 0 &&
                    strlen($this->publication) != 0 &&
                    strlen($this->editors) != 0 &&
                    strlen($this->places) != 0 &&
                    strlen($this->publishingHouse) != 0 &&
                    strlen($this->pages) != 0){
                $lFormattedString .= $this->authors;
                $lFormattedString .= " (";
                $lFormattedString .= $this->year;
                $lFormattedString .= "). ";
                $lFormattedString .= $this->title;
                $lFormattedString .= ". In ";
                $lFormattedString .= $this->editors;
                $lFormattedString .= " (Hrsg.), <i>";
                $lFormattedString .= $this->publication;
                $lFormattedString .= "</i>";
                if(strlen($this->volume) > 0){
                    $lFormattedString .= " (Bd. ";
                    $lFormattedString .= $this->volume;
					$lFormattedString .= ", S. ";
					$lFormattedString .= $this->pages;					
					$lFormattedString .= ")";
                }
				else {
					$lFormattedString .= " (S. ";
					$lFormattedString .= $this->pages;
					$lFormattedString .= ")";
				}
                $lFormattedString .= ". ";
                $lFormattedString .= $this->places;
                $lFormattedString .= ": ";
                $lFormattedString .= $this->publishingHouse;
                $lFormattedString .= ".";
            }
            // If document type cannot be determined, use minimal citation
            else{
                if(strlen($this->authors) > 0){
                    $lFormattedString .= $this->authors;
                    $lFormattedString .= " ";
                }
                $lFormattedString .= $this->title;
                $lFormattedString .= ".";
            }

            return $lFormattedString;
    }

}

