<?php
use PHPUnit\Framework\TestCase;

class ExpectedErrorTest extends TestCase
{
    
    public function testFailingInclude()
    {
        try {
            include 'not_existing_file.php';
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }
    }
}
?>