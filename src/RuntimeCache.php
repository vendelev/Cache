<?php

namespace vendelev\cache;

/**
 * @package vendelev\cache
 */
class RuntimeCache
{
    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var string
     */
    protected $separate = '/';

    /**
     * @var RuntimeCache
     */
    protected static $instance = null;

    /**
     * @return RuntimeCache
     */
    public static function me()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $key 'services/service/version'
     * @param mixed  $value
     *
     * @return bool
     */
    public function add($key, $value)
    {
        $retunValue = false;

        if (!$this->has($key)) {
            $this->set($key, $value);
            $retunValue = true;
        }

        return $retunValue;
    }

    /**
     * @param string $key 'services/service/version'
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $path = explode($this->getSeparate(), $key);
        $node = &$this->cache;
        $cnt  = count($path) - 1;

        for ($ii=0; $ii<=count($path); $ii++) {
            if ($ii == $cnt) {
                $node[$path[$ii]] = $value;
                break;
            } elseif (!array_key_exists($path[$ii], $node)) {
                $node[$path[$ii]] = [];
            }
            if (!is_array($node[$path[$ii]])) {
                $node[$path[$ii]] = [];
            }

            $node = &$node[$path[$ii]];
        }

        return $this;
    }

    /**
     * @param string    $key 'services/service/version'
     * @param mixed     $defaultValue
     *
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        $parts     = explode($this->getSeparate(), $key);
        $keyExists = true;
        $node      = $this->getAll();

        foreach($parts as $part) {
            $part = trim($part);

            if (!is_array($node) || !array_key_exists($part, $node)) {
                $keyExists = false;
                break;
            }

            $node = &$node[$part];
        }

        if ($keyExists) {
            return $node;
        } else {
            return $defaultValue;
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        $parts     = explode($this->getSeparate(), $key);
        $keyExists = true;
        $node      = $this->getAll();

        foreach($parts as $part) {
            $part = trim($part);

            if (!is_array($node) || !array_key_exists($part, $node)) {
                $keyExists = false;
                break;
            }

            $node = &$node[$part];
        }

        return ($keyExists);

    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $retunValue = false;
        $path = explode($this->getSeparate(), $key);
        $node = &$this->cache;
        $cnt  = count($path) - 1;

        for ($ii=0; $ii<=count($path); $ii++) {
            if ($ii == $cnt) {
                unset($node[$path[$ii]]);
                $retunValue = true;
                break;
            }

            $node = &$node[$path[$ii]];
        }
        return $retunValue;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->cache;
    }

    /**
     * @param array $cache
     *
     * @return $this
     */
    public function setAll($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeparate()
    {
        return $this->separate;
    }

    /**
     * @param string $separate
     *
     * @return $this
     */
    public function setSeparate($separate)
    {
        $this->separate = $separate;

        return $this;
    }
}