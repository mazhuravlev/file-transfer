<?php

use mazhuravlev\FileTransfer\ConnectionInterface;
use mazhuravlev\FileTransfer\Factory;
use mazhuravlev\FileTransfer\FTPConnection;
use mazhuravlev\FileTransfer\SFTPConnection;

class FactoryTest extends PHPUnit_Framework_TestCase
{

    public function testGetFtpConnection()
    {
        $ftpConnection = Factory::getConnection(Factory::TYPE_FTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
        $this->assertInstanceOf(FTPConnection::class, $ftpConnection);
        $this->assertInstanceOf(ConnectionInterface::class, $ftpConnection);
    }

    public function testGetSftpConnection()
    {
        $sftpConnection = Factory::getConnection(Factory::TYPE_SFTP, SFTP_HOST, SFTP_USER, SFTP_PASS);
        $this->assertInstanceOf(SFTPConnection::class, $sftpConnection);
        $this->assertInstanceOf(ConnectionInterface::class, $sftpConnection);
    }
}
