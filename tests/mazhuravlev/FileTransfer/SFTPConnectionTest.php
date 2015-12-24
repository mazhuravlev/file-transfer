<?php

require_once 'ConnectionTest.php';

use mazhuravlev\FileTransfer\Factory;

class SFTPConnectionTest extends ConnectionTest
{

    public function setUp()
    {
        $this->connection = Factory::getConnection(Factory::TYPE_SFTP, Env::SFTP_HOST, Env::SFTP_USER, Env::SFTP_PASS);
    }

    public function testClose()
    {
        $sftpConnection = Factory::getConnection(Factory::TYPE_SFTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
        $sftpConnection->close();
    }

}
