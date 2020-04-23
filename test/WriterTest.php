<?php

namespace Lianhua\Test;

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
 * @file WriterTest.php
 * @author Camille Nevermind
 */

/**
 * @brief Tests the writer
 * @class WriterTest
 * @package Lianhua\Test
 */
class WriterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @brief Tests the writing of a simple CSV
     * @return void
     */
    public function testWriteCsv(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true);

        $writer->write(["A", "B", "C D", "E\"F"]);
        $writer->write(["G", "H,I", "J", "K"]);

        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv", $file);
    }

    /**
     * @brief Tests the writing of a simple CSV
     * @return void
     */
    public function testWriteCsvWithCustomChars(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true, "!", "?");

        $writer->write(["A", "B", "C D", "E?F"]);
        $writer->write(["G", "H!I", "J", "K"]);

        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "02.csv", $file);
    }

    /**
     * @brief Tests the whiting of a whole file
     * @return void
     */
    public function testWriteAll(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true);

        $data = [];
        $data[] = ["A", "B", "C D", "E\"F"];
        $data[] = ["G", "H,I", "J", "K"];

        $writer->writeAll($data);
        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv", $file);
    }

    /**
     * @brief Tests the trim option
     * @return void
     */
    public function testTrim(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true);
        $writer->trimRecords(true);

        $writer->write(["      A    ", "       B  ", "    C D", "E\"F      "]);
        $writer->write(["   G     ", "   H,I     ", "J    ", "    K   "]);

        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv", $file);
    }

    /**
     * @brief Tests the ignore empty lines option
     * @return void
     */
    public function testIgnoreEmptyLines(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true);
        $writer->ignoreEmptyRecords(true);

        $writer->write(["A", "B", "C D", "E\"F"]);
        $writer->write([]);
        $writer->write(["", "", "", "", ""]);
        $writer->write(["G", "H,I", "J", "K"]);

        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv", $file);
    }

    /**
     * @brief Tests with headers
     * @return void
     */
    public function testWithHeaders(): void
    {
        $file = tempnam(sys_get_temp_dir(), "SuperCSV");
        $writer = new \Lianhua\SuperCSV\Writer($file, true);
        $writer->setHeader(["Data1", "Data2", "Data3", "Data4"]);

        $writer->write(["Data1" => "AZAZ", "Data2" => "SDQSD", "Data3" => "THRT", "Data4" => "ZERAA"]);
        $writer->write(["Data1" => "SDVDS", "Data2" => "GERHY", "Data3" => "ADAEGEGZ", "Data4" => "AZEAZR"]);

        unset($writer);

        $this->assertFileEquals(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "05.csv", $file);
    }
}
