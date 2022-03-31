<?php

use ArrayAccess;
use Iterator;

/**
 * @implements Iterator<string>
 * @implements ArrayAccess<int, string>
 */
class UTF8String implements Iterator, ArrayAccess
{
    /**
     * @var string
     */
    private string $text;

    /**
     * @var int
     */
    private int $index = 0;

    /**
     * @var string
     */
    private string $utf8Char = "";

    /**
     * @var int
     * how many bytes, not char lenght
     */
    private int $len;

    /**
     * @var int
     * how many char so far;
     */
    private int $charCounter = 0;

    /**
     * @var int
     * length in char not byte
     * **/
    private int $textLen = 0;

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->text = $string;
        $this->len = strlen($string);
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return string Can return any type.
     */
    public function current(): string
    {
        return $this->utf8Char;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        $this->utf8Char = '';
        $char = $this->text[$this->index];
        $byteLen = $this->charCodeLength($char);

        while ($byteLen > 0) {
            --$byteLen;
            $this->utf8Char .= $this->text[$this->index];
            $this->index++;
        }

        ++$this->charCounter;
    }

    /**
     * @param string $char
     * @return int
     */
    private function charCodeLength(string $char): int
    {
        $charCode = ord($char);
        if ($charCode < 128) {
            $byteLen = 1;
        } elseif ($charCode < 224) {
            $byteLen = 2;
        } elseif ($charCode < 240) {
            $byteLen = 3;
        } else {
            $byteLen = 4;
        }
        return $byteLen;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return int
     */
    public function key(): int
    {
        return $this->charCounter;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return ($this->index < $this->len);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        $this->index = 0;
        $this->charCounter = 0;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param int $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset): bool
    {
        if ($this->textLen > 0) {
            //we know string length
            return $offset >= 0 && $offset < $this->textLen;
        }

        $pre = $offset - 1;
        $localIndex = 0;
        while ($pre >= 0) {
            $char = $this->text[$localIndex];
            $charLen = $this->charCodeLength($char);
            $localIndex += $charLen;
            --$pre;
        }

        if (isset($this->text[$localIndex]) === false) {
            return false;
        }

        $char = $this->text[$localIndex];
        $charLen = $this->charCodeLength($char);
        ++$localIndex; //next char
        --$charLen; // since this is for next char, so charLen minus 1
        while ($charLen > 0) {
            if (isset($this->text[$localIndex]) === false) {
                return false;
            }
            --$charLen;
            ++$localIndex;
        }

        return true;
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param int $offset <p>
     * The offset to retrieve.
     * </p>
     * @return string Can return all value types.
     */
    public function offsetGet($offset): string
    {
        $pre = $offset - 1;
        $localIndex = 0;
        while ($pre >= 0) {
            $char = $this->text[$localIndex];
            $charLen = $this->charCodeLength($char);
            $localIndex += $charLen;
            --$pre;
        }

        $str = "";
        if (isset($this->text[$localIndex]) === true) {
            $char = $this->text[$localIndex];
            $charLen = $this->charCodeLength($char);

            while ($charLen > 0) {
                --$charLen;
                $str .= $this->text[$localIndex];
                ++$localIndex;
            }
        }
        return $str;
    }

    /**
     * @param int    $offset
     * @param string $value
     * @return UTF8String
     */
    public function offsetSet($offset, $value): UTF8String
    {
        $str = "";
        $pre = $offset - 1;
        $localIndex = 0;

        while ($pre >= 0 && isset($this->text[$localIndex]) === true) {
            $char = $this->text[$localIndex];
            $charLen = $this->charCodeLength($char);
            while ($charLen > 0) {
                --$charLen;
                $str .= $this->text[$localIndex];
                $localIndex++;
            }
            --$pre;
        }//previous bytes copied

        if (isset($this->text[$localIndex]) === true) {
            $char = $this->text[$localIndex];
            $charLen = $this->charCodeLength($char);
            while ($charLen > 0) {
                --$charLen;
                ++$localIndex;
            }
        }//index passed old char

        $str .= $value;

        while (isset($this->text[$localIndex]) === true) {
            $str .= $this->text[$localIndex];
            ++$localIndex;
        }

        return new UTF8String($str);
    }

    /**
     * @param int $offset
     * @return UTF8String
     */
    public function offsetUnset($offset): UTF8String
    {
        return $this->offsetSet($offset, "");
    }

    /**
     * @return int
     */
    public function strlen(): int
    {
        if ($this->textLen > 0) {
            return $this->textLen;
        }

        $localIndex = 0;
        $localCharCounter = 0;

        while (isset($this->text[$localIndex]) === true) {
            $char = $this->text[$localIndex];
            $byteLen = $this->charCodeLength($char);

            while ($byteLen > 0) {
                --$byteLen;
                $localIndex++;
            }
            ++$localCharCounter;
        }

        $this->textLen = $localCharCounter;
        return $this->textLen;
    }
}
