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
 * @file Reader.php
 * @author Camille Nevermind
 */

/**
 * @class Reader
 * @package Lianhua\SuperCSV
 * @brief Reader class
 */
class Reader
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
     * @param $file File path
     * @return void
     */
    private function openFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new \Exception("File not found: " . $file, 1);
        }

        if ($this->file !== null) {
            fclose($this->file);
        }

        $this->file = fopen($file, "r");

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
     * @return array|null Fields read, with assoc if a header has been read
     */
    public function read(): ?array
    {
        $res = fgetcsv($this->file, 0, $this->delimiter, $this->enclosure, $this->escaper);

        if ($res === false) {
            return null;
        }

        if ($res[0] === null) {
            return $this->read();
        }

        if ($this->trim) {
            $res = array_map("trim", $res);
        }

        if ($this->ignoreEmpty && $this->isEmpty($res)) {
            return $this->read();
        }

        if ($this->header !== null) {
            $assoc = [];

            foreach ($res as $key => $value) {
                $assoc[$this->header[$key]] = $value;
            }

            return $assoc;
        }

        return $res;
    }

    /**
     * @brief Loads the header
     * @return void
     */
    public function getHeader(): void
    {
        $this->header = $this->read();
    }

    /**
     * @brief Returns the whole CSV (beware of memory consumption!)
     * @return array All data from CSV file, with assoc if a header has been read
     */
    public function readAll(): array
    {
        $res = [];

        while ($row = $this->read()) {
            $res[] = $row;
        }

        $this->rewind();

        return $res;
    }

    /**
     * @brief Rewinds the CSV (the header will automatically reload)
     * @return void
     */
    public function rewind(): void
    {
        rewind($this->file);

        if ($this->header !== null) {
            $this->getHeader();
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
     * @param string $file CSV file path
     * @param string $delimiter The delimiter
     * @param string $enclosure The enclosure
     * @param string $escaper The escaper
     * @return void
     */
    public function __construct(
        string $file = null,
        string $delimiter = ",",
        string $enclosure = "\"",
        string $escaper = "\\"
    ) {
        $this->file = null;
        $this->header = null;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escaper = $escaper;
        $this->ignoreEmpty = false;
        $this->trim = false;

        if ($file !== null) {
            $this->openFile($file);
        }
    }

    /**
     * @brief Destructor
     * @return void
     */
    public function __destruct()
    {
        fclose($this->file);
    }
}
