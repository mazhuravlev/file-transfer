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
        $sftpConnection = Factory::getConnection(Factory::TYPE_SFTP, Env::SFTP_HOST, Env::SFTP_USER, Env::SFTP_PASS);
        $this->assertInstanceOf(SFTPConnection::class, $sftpConnection);
        $this->assertInstanceOf(ConnectionInterface::class, $sftpConnection);
    }

    public function testWrongConnectionType()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        Factory::getConnection('mysql', '', '', '');
    }
}
