<?php

namespace JLaso\Iterators;

use JLaso\Iterators\Exception\WrongParameterType;

/**
 * take a look over the example of use in the root of this project
 */
class VariableIterator implements \Iterator
{
    /** @var array */
    protected $options;
    /** @var array */
    protected $current;
    /** @var array */
    protected $accounting;
    /** @var int */
    protected $position;
    /** @var int */
    protected $numOptions;
    /** @var array */
    protected $optionKeys;

    /**
     * create a new VariableIterator with the complex options you need
     * @param array $options
     * @throws WrongParameterType
     */
    public function __construct(array $options)
    {
        foreach($options as $option){
            foreach($option as $value){
                if(is_array($value)){
                    throw new WrongParameterType('Found multidimensional array');
                }
            }
        }
        $this->options = $options;
        $this->rewind();
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $level = 0;
        do{
            $key = $this->optionKeys[$level];
            list($current,$max) = $this->accounting[$key];
            if($current+1 < $max){
                $this->accounting[$key][0]++;
                break;
            }else{
                $this->accounting[$key][0] = 0;
                $level++;
            }
        }while($level < count($this->options));
        $this->sync();
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if ($this->valid()) {
            return $this->position;
        }
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        return $this->position < $this->numOptions;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind()
    {
        $accounting = [];
        $this->numOptions = 1;
        foreach($this->options as $key=>$value){
            $this->optionKeys[] = $key;
            $accounting[$key] = [0, count($value)];
            $this->numOptions *= count($value);
        }
        $this->accounting = $accounting;
        $this->position = 0;
        $this->sync();
    }

    /**
     * internal to generate current variation according to the value of accounting
     */
    protected function sync()
    {
        $current = [];
        foreach($this->accounting as $key=>$value){
            $current[$key] = $this->options[$key][$value[0]];
        }
        $this->current = $current;
    }

}