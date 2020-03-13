<?php namespace Lianhua\Test;

class ReaderTest extends \PHPUnit\Framework\TestCase
{
    public function testReadCsv()
    {
        $reader = new \Lianhua\SuperCSV\Reader(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "01.csv");

        //Line 1
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertEquals("A", $line[0]);
        $this->assertEquals("B", $line[1]);
        $this->assertEquals("C D", $line[2]);
        $this->assertEquals("E\"F", $line[3]);

        //Line 2
        $line = $reader->read();
        $this->assertIsArray($line);
        $this->assertEquals("G", $line[0]);
        $this->assertEquals("H,I", $line[1]);
        $this->assertEquals("J", $line[2]);
        $this->assertEquals("K", $line[3]);

        //End of file
        $line = $reader->read();
        $this->assertNull($line);
    }
}
