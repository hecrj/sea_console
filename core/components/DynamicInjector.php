<?php

namespace Core\Components;

abstract class DynamicInjector
{
	protected $classes;
	protected $dependencies;
	protected $shared = true;
	private   $instances = array();
	
	public function __construct()
	{}
	
	public function set($name, $instance)
	{
		$this->instances[$name] = $instance;
	}
    
    public function getClassName($name)
    {
        return $this->classes[$name];
    }
	
	public function get($name)
	{
		if(isset($this->instances[$name]))
			return $this->instances[$name];
					
		if(!isset($this->classes[$name]))
			throw new \RuntimeException('Unsetted class name: '. $name .'. Check your class: '. get_class($this));
		
		return $this->saveInstance($name, $this->inject($this->classes[$name], $this->dependencies[$name]));
	}
	
	private function saveInstance($name, $instance)
	{
		if($this->shared !== FALSE and ($this->shared === TRUE or in_array($name, $this->shared)))
			$this->instances[$name] = $instance;
		
		return $instance;
	}
	
	public function inject($class_name, $dependencies = null)
	{
		$injections = array();
		
		if(is_array($dependencies))
			foreach($dependencies as $dependency)
				$injections[] = $this->getDependency($dependency);
		
		$inject_num = count($injections);
		
		switch($inject_num)
		{
			case 0: $instance = new $class_name(); break;
		    case 1: $instance = new $class_name($injections[0]); break;
		    case 2: $instance = new $class_name($injections[0], $injections[1]); break;
		    case 3: $instance = new $class_name($injections[0], $injections[1], $injections[2]); break;
		    case 4: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3]); break;
		    case 5: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3], $injections[4]); break;
		    case 6: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3], $injections[4], $injections[5]); break;
		    case 7: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3], $injections[4], $injections[5], $injections[6]); break;
		    case 8: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3], $injections[4], $injections[5], $injections[6], $injections[7]); break;
		    case 9: $instance = new $class_name($injections[0], $injections[1], $injections[2], $injections[3], $injections[4], $injections[5], $injections[6], $injections[7], $injections[8]); break;
		    
			default:
				$r = new ReflectionClass($class_name);
				$instance = $r->newInstanceArgs($injections);
		}
		
		return $instance;
	}
	
	private function getDependency($name)
	{
		if(isset($this->instances[$name]))
			return $this->instances[$name];
		
		if(!isset($this->classes[$name]))
		{
			if(null !== $this->injector)
				return $this->injector->get($name);
			else
				throw new \RuntimeException('Dependency not present in classes list or in instances of the injector: '. $name .'. Check your class: '. get_class($this));
		}
		
		return $this->saveInstance($name, $this->inject($this->classes[$name], $this->dependencies[$name]));
	}
}

?>