<?php

namespace Lianhua\Test;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidArgumentException;

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
 * @file ReaderTest.php
 * @author Camille Nevermind
 */

/**
 * @brief Tests the reader
 * @class ReaderTest
 * @package Lianhua\Test
 */
class ReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @brief Tests the reading of a simple file
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testReadCsv(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv");

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("A", $line[0]);
        $this->assertEquals("B", $line[1]);
        $this->assertEquals("C D", $line[2]);
        $this->assertEquals("E\"F", $line[3]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("G", $line[0]);
        $this->assertEquals("H,I", $line[1]);
        $this->assertEquals("J", $line[2]);
        $this->assertEquals("K", $line[3]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }

    /**
     * @brief Tests the reading of a simple file with custom delimiter
     * @return void
     * @throws ExpectationFailedException
     */
    public function testReadCsvWithCustomChars(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(
            __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "02.csv",
            "!",
            "?"
        );

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertEquals("A", $line[0]);
        $this->assertEquals("B", $line[1]);
        $this->assertEquals("C D", $line[2]);
        $this->assertEquals("E?F", $line[3]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertEquals("G", $line[0]);
        $this->assertEquals("H!I", $line[1]);
        $this->assertEquals("J", $line[2]);
        $this->assertEquals("K", $line[3]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }

    /**
     * @brief Tests the read of the entire file
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testReadAll(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv");
        $data = $reader->readAll();

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertIsArray($data[0]);
        $this->assertCount(4, $data[0]);
        $this->assertEquals("A", $data[0][0]);
        $this->assertEquals("B", $data[0][1]);
        $this->assertEquals("C D", $data[0][2]);
        $this->assertEquals("E\"F", $data[0][3]);
        $this->assertIsArray($data[1]);
        $this->assertCount(4, $data[1]);
        $this->assertEquals("G", $data[1][0]);
        $this->assertEquals("H,I", $data[1][1]);
        $this->assertEquals("J", $data[1][2]);
        $this->assertEquals("K", $data[1][3]);
    }

    /**
     * @brief Tests the trim option
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testTrim(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "03.csv");
        $reader->trimRecords(true);

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("A", $line[0]);
        $this->assertEquals("B", $line[1]);
        $this->assertEquals("C D", $line[2]);
        $this->assertEquals("E\"F", $line[3]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("G", $line[0]);
        $this->assertEquals("H,I", $line[1]);
        $this->assertEquals("J", $line[2]);
        $this->assertEquals("K", $line[3]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }

    /**
     * @brief Tests the ignore empty lines option
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testIgnoreEmptyLines(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "04.csv");
        $reader->ignoreEmptyRecords(true);

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("A", $line[0]);
        $this->assertEquals("B", $line[1]);
        $this->assertEquals("C D", $line[2]);
        $this->assertEquals("E\"F", $line[3]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertEquals("G", $line[0]);
        $this->assertEquals("H,I", $line[1]);
        $this->assertEquals("J", $line[2]);
        $this->assertEquals("K", $line[3]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }

    /**
     * @brief Tests reading with a header
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testWithHeaders(): void
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "05.csv");
        $reader->getHeader();

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertArrayHasKey("Data1", $line);
        $this->assertArrayHasKey("Data2", $line);
        $this->assertArrayHasKey("Data3", $line);
        $this->assertArrayHasKey("Data4", $line);
        $this->assertEquals("AZAZ", $line["Data1"]);
        $this->assertEquals("SDQSD", $line["Data2"]);
        $this->assertEquals("THRT", $line["Data3"]);
        $this->assertEquals("ZERAA", $line["Data4"]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertCount(4, $line);
        $this->assertArrayHasKey("Data1", $line);
        $this->assertArrayHasKey("Data2", $line);
        $this->assertArrayHasKey("Data3", $line);
        $this->assertArrayHasKey("Data4", $line);
        $this->assertEquals("SDVDS", $line["Data1"]);
        $this->assertEquals("GERHY", $line["Data2"]);
        $this->assertEquals("ADAEGEGZ", $line["Data3"]);
        $this->assertEquals("AZEAZR", $line["Data4"]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }
}
