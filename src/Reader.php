<?php namespace Lianhua\SuperCSV;

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
    /** @brief Delimiter (, by default) */
    private $delimiter;
    /** @brief Enclosure (" by default) */
    private $enclosure;
    /** @brief Escaper (\ by default) */
    private $escaper;
    /** @brief CSV file handler */
    private $file;
    /** @brief Loaded header */
    private $header;
    /** @brief Skip empty records parameter */
    private $ignoreEmpty;
    /** @brief Trim parameter */
    private $trim;

    /**
     * @brief Open CSV file
     * @param $file File path
     */
    public function openFile(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception("File not found: " . $file, 1);
        }

        if ($this->file !== null) {
            fclose($this->file);
        }

        $this->file = fopen($file, "r");
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
     */
    public function getHeader()
    {
        $this->header = $this->read();
    }

    /**
     * @brief Returns the whole CSV (beware of memory consumption!)
     * @return array All data from CSV file, with assoc if a header has been read
     */
    public function fetchAll(): array
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
     */
    public function setDelimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @brief Sets the enclosure
     * @param string $enclosure The enclosure
     */
    public function setEnclosure(string $enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @brief Sets the escaper
     * @param string $escaper The escaper
     */
    public function setEscaper(string $escaper)
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
     */
    public function __destruct()
    {
        fclose($this->file);
    }
}
