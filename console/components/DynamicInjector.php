<?php

namespace Console\Components;

abstract class DynamicInjector
{
	protected $classes;
	protected $dependencies;
	protected $shared = array();
	private   $instances = array();
	
	public function __construct()
	{
	}
	
	public function set($name, $instance)
	{
		$this->instances[$name] = $instance;
	}
    
    public function getClassName($name)
    {
        return $this->classes[$name];
    }
	
	public function get($name, Array $params = null)
	{
		if(isset($this->instances[$name]))
			return $this->instances[$name];
					
		if(!isset($this->classes[$name]))
			throw new \RuntimeException('Unsetted class name: '. $name .'. Check your class: '. get_class($this));
		
		return $this->saveInstance($name, $this->inject($name, $params));
	}
	
	private function saveInstance($name, $instance)
	{
		if(in_array($name, $this->shared))
			$this->instances[$name] = $instance;
		
		return $instance;
	}
	
	private function inject($name, Array $params = null)
	{
		$class_name = $this->classes[$name];
		$dependencies = array();

		if(isset($this->dependencies[$name]))
			$dependencies = $this->dependencies[$name];
		
		foreach($dependencies as $dependency)
			$params[] = $this->getDependency($dependency);
		
		$num_params = count($params);
		
		switch($num_params)
		{
			case 0: $instance = new $class_name(); break;
		    case 1: $instance = new $class_name($params[0]); break;
		    case 2: $instance = new $class_name($params[0], $params[1]); break;
		    case 3: $instance = new $class_name($params[0], $params[1], $params[2]); break;
		    case 4: $instance = new $class_name($params[0], $params[1], $params[2], $params[3]); break;
		    case 5: $instance = new $class_name($params[0], $params[1], $params[2], $params[3], $params[4]); break;
		    case 6: $instance = new $class_name($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]); break;
		    case 7: $instance = new $class_name($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]); break;
		    case 8: $instance = new $class_name($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]); break;
		    case 9: $instance = new $class_name($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]); break;
		    
			default:
				$r = new ReflectionClass($class_name);
				$instance = $r->newInstanceArgs($params);
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
		
		return $this->saveInstance($name, $this->inject($name));
	}
}

?>