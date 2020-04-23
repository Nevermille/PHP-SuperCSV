<?php

namespace Lianhua\SuperCSV;

/*
SuperCSV Library
Copyright (C) 2020  Lianhua Studio

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * @file Writer.php
 * @author Camille Nevermind
 */

/**
 * @class Writer
 * @package Lianhua\SuperCSV
 * @brief Writer class
 */
class Writer
{
    /**
     * @brief Delimiter (, by default)
     * @var string
     */
    private $delimiter;

    /**
     * @brief Enclosure (" by default)
     * @var string
     */
    private $enclosure;

    /**
     * @brief Escaper (\ by default)
     * @var string
     */
    private $escaper;

    /**
     * @brief CSV file handler
     * @var resource
     */
    private $file;

    /**
     * @brief Loaded header
     * @var array
     */
    private $header;

    /**
     * @brief Skip empty records parameter
     * @var bool
     */
    private $ignoreEmpty;

    /**
     * @brief Trim parameter
     * @var bool
     */
    private $trim;

    /**
     * @brief Open CSV file
     * @param string $file File path
     * @param bool $overwrite Truncate file
     * @return void
     */
    private function openFile(string $file, bool $overwrite): void
    {
        if ($this->file !== null) {
            fclose($this->file);
        }

        $this->file = fopen($file, $overwrite ? "w" : "a");

        if ($this->file === null) {
            throw new \Exception("Unable to open: " . $file, 2);
        }
    }

    /**
     * @brief Ignore empty records when reading
     * @param bool $val The parameter value
     * @return void
     */
    public function ignoreEmptyRecords(bool $val): void
    {
        $this->ignoreEmpty = $val;
    }

    /**
     * @brief Trim values when reading
     * @param bool $val The parameter value
     * @return void
     */
    public function trimRecords(bool $val): void
    {
        $this->trim = $val;
    }

    /**
     * @brief Check if record is empty
     * @param array $values Fields values
     * @return bool true if empty, false otherwise
     */
    private function isEmpty(array $values): bool
    {
        foreach ($values as $value) {
            if ($value !== "") {
                return false;
            }
        }

        return true;
    }

    /**
     * @brief Read the next line
     * @return void
     */
    public function write(array $data): void
    {
        $res = $data;

        if ($this->trim) {
            $res = array_map("trim", $data);
        }

        if ($this->ignoreEmpty && $this->isEmpty($res)) {
            return;
        }

        if ($this->header !== null) {
            $assoc = [];

            foreach ($res as $key => $value) {
                $assoc[$this->header[$key]] = $value;
            }

            fputcsv($this->file, $assoc, $this->delimiter, $this->enclosure, $this->escaper);
        } else {
            fputcsv($this->file, $res, $this->delimiter, $this->enclosure, $this->escaper);
        }
    }

    /**
     * @brief Loads the header
     * @return void
     */
    public function setHeader(array $header): void
    {
        $this->header = [];

        foreach ($header as $key => $value) {
            $this->header[$value] = $key;
        }

        rewind($this->file);
        fputcsv($this->file, $header, $this->delimiter, $this->enclosure, $this->escaper);
    }

    /**
     * @brief Returns the whole CSV (beware of memory consumption!)
     * @return void
     */
    public function writeAll(array $datas): void
    {
        foreach ($datas as $data) {
            $this->write($data);
        }
    }

    /**
     * @brief Sets the delimiter
     * @param string $delimiter The delimiter
     * @return void
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @brief Sets the enclosure
     * @param string $enclosure The enclosure
     * @return void
     */
    public function setEnclosure(string $enclosure): void
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @brief Sets the escaper
     * @param string $escaper The escaper
     * @return void
     */
    public function setEscaper(string $escaper): void
    {
        $this->escaper = $escaper;
    }

    /**
     * @brief Constructor
     * @param $file CSV file path
     * @param $delimiter The delimiter
     * @param $enclosure The enclosure
     * @param $escaper The escaper
     */
    public function __construct(
        string $file = null,
        bool $overwrite,
        string $delimiter = ",",
        string $enclosure = "\"",
        string $escaper = "\\"
    ) {
        $this->file = null;
        $this->header = null;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escaper = $escaper;

        if ($file !== null) {
            $this->openFile($file, $overwrite);
        }
    }

    /**
     * @brief Destructor
     */
    public function __destruct()
    {
        fclose($this->file);
    }
}
