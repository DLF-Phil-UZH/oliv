<?php
/**
 *
 * Abstract class for models
 * @author Thomas Bernhart
 *
 */
abstract class Abstract_base_model extends CI_Model
{
	protected $id;

	/**
	 * Gets namespace of class (Application_ or Admin_ for example)
	 *
     	 * @param string $className
     	 * @return string
	 */
	public static function getClassNamespace($className)
	{
        	if (is_object($className)) {
        		$className = get_class($className);
        	}
        	return substr($className, 0, strpos($className, '_'));
	}

	/**
	 * Gets the type of model class (User or Entry for example)
   	 *
	 * @param string $className
	 * @return string
	 */
   	public static function getClassType($className)
    	{
       		if (is_object($className)) {
            		$className = get_class($className);
        	}
        	return substr($className, strrpos($className, '_') + 1);
	}	

	public function __set($name, $value) {
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid property');
		}
		$this->$method($value);
	}

	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid property');
		}
		return $this->$method();
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}


	/* getters: */

	public function getId()
	{
		return $this->id;
	}


	/* setters: */

	public function setId( $id )
	{
		$this->id = $id;
		return $this;
	}

	/**
	 *
	 */
	public function isNew() {
    	$lIsNew = false;
		if($this->id == null){
			$lIsNew = true;
		}
		return $lIsNew;
	}	

	public function toArray() {
		$arr = array('id' => $this->id);

		return $arr;
	}
}

