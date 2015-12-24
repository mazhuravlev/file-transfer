<?php

use mazhuravlev\FileTransfer\ConnectionException;
use mazhuravlev\FileTransfer\ConnectionInterface;
use mazhuravlev\FileTransfer\Factory;
use mazhuravlev\FileTransfer\FTPConnection;

class ConnectionTest extends PHPUnit_Framework_TestCase
{

    /** @var  ConnectionInterface */
    protected $connection;

    public function testPwd()
    {
        $this->assertInternalType('string', $this->connection->pwd());
    }

    public function testCd()
    {
        $this->assertInstanceOf(ConnectionInterface::class, $this->connection->cd(Env::FTP_CD_DIR));
    }

    public function testCdPwd()
    {
        $this->connection->cd(Env::FTP_CD_DIR);
        $this->assertStringEndsWith(Env::FTP_CD_DIR, $this->connection->pwd());
    }

    public function testDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'd_');
        $this->assertFileExists($filename);
        unlink($filename);
        $connection = $this->connection->download(Env::FTP_DOWNLOAD_FILE, $filename, true);
        $this->assertFileExists($filename);
        unlink($filename);
        $this->assertInstanceOf(ConnectionInterface::class, $connection);
    }

    public function testDownloadExistingFile()
    {
        $filename = tempnam(Env::TMP_DIR, 'd_');
        $this->setExpectedException(ConnectionException::class);
        $this->connection->download(Env::SFTP_DOWNLOAD_FILE, $filename);
    }

    public function testDownloadUnexistntFile()
    {
        $filename = tempnam(Env::TMP_DIR, 'd_');
        $this->setExpectedException(ConnectionException::class);
        $this->connection->download(uniqid('', true), $filename, true);
    }

    public function testUploadUnexistentFile()
    {
        $filename = uniqid('', true);
        $this->setExpectedException(ConnectionException::class);
        $this->connection->upload($filename, $filename);
    }

    public function testUpload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        file_put_contents($filename, str_repeat(sha1(time()), 10));
        $connection = $this->connection->upload($filename, basename($filename));
        unlink($filename);
        $this->connection->delete(basename($filename));
        $this->assertInstanceOf(ConnectionInterface::class, $connection);
    }

    public function testUploadDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        $fileContents = str_repeat(sha1(time()), 10);
        file_put_contents($filename, $fileContents);
        $this->connection->upload($filename, basename($filename));
        unlink($filename);
        $this->connection->download(basename($filename), $filename);
        $this->assertFileExists($filename);
        $this->assertEquals($fileContents, file_get_contents($filename));
        $this->connection->delete(basename($filename));
        unlink($filename);
    }

    public function testExec()
    {
        if(Env::FTP_EXEC) {
            $this->connection->exec('ls -la');
        }
    }

}
