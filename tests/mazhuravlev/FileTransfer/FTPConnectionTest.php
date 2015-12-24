<?php

require_once 'ConnectionTest.php';

use mazhuravlev\FileTransfer\Factory;

class FTPConnectionTest extends ConnectionTest
{

    public function setUp()
    {
        $this->connection = Factory::getConnection(Factory::TYPE_FTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
    }

    public function testClose()
    {
        $ftpConnection = Factory::getConnection(Factory::TYPE_FTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
        $ftpConnection->close();
    }

}
