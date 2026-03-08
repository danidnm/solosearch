<?php

namespace SoloSearch\Core\Model;

abstract class AbstractModel
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * Set data
     * 
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Get data
     * 
     * @param string $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if ($key === null) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
    }

    /**
     * Check if data exists
     * 
     * @param string $key
     * @return bool
     */
    public function hasData($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Magic methods for getters and setters
     */
    public function __call($name, $arguments)
    {
        $method = substr($name, 0, 3);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', substr($name, 3)));

        if ($method === 'get') {
            return $this->getData($key);
        }

        if ($method === 'set') {
            return $this->setData($key, $arguments[0] ?? null);
        }

        throw new \Exception("Method {$name} not found in " . get_class($this));
    }

    /**
     * Get identity value
     * 
     * @return mixed
     */
    public function getId()
    {
        // We assume 'id' is a common default for all models
        return $this->getData('id');
    }
}
