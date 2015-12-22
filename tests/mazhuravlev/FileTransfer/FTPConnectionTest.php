<?php

use mazhuravlev\FileTransfer\ConnectionException;
use mazhuravlev\FileTransfer\Factory;
use mazhuravlev\FileTransfer\FTPConnection;

class FTPConnectionTest extends PHPUnit_Framework_TestCase
{

    /** @var  FTPConnection */
    private $ftpConnection;

    public function setUp()
    {
        $this->ftpConnection = Factory::getConnection(Factory::TYPE_FTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
    }

    public function testPwd()
    {
        $this->assertInternalType('string', $this->ftpConnection->pwd());
    }

    public function testCd()
    {
        $this->assertInstanceOf(FTPConnection::class, $this->ftpConnection->cd(Env::FTP_CD_DIR));
    }

    public function testCdPwd()
    {
        $this->ftpConnection->cd(Env::FTP_CD_DIR);
        $this->assertStringEndsWith(Env::FTP_CD_DIR, $this->ftpConnection->pwd());
    }

    public function testDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'd_');
        $this->assertFileExists($filename);
        try {
            $this->ftpConnection->download(Env::FTP_DOWNLOAD_FILE, $filename);
        } catch (Exception $e) {
            $this->assertInstanceOf(ConnectionException::class, $e);
        }
        $this->ftpConnection->download(Env::FTP_DOWNLOAD_FILE, $filename, true);
        unlink($filename);
        $this->ftpConnection->download(Env::FTP_DOWNLOAD_FILE, $filename);
        $this->assertFileExists($filename);
        unlink($filename);
    }

    public function testUpload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        file_put_contents($filename, str_repeat(sha1(time()), 10));
        $this->ftpConnection->upload($filename, basename($filename));
        $this->ftpConnection->delete(basename($filename));
        unlink($filename);
    }

    public function testUploadDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        $fileContents = str_repeat(sha1(time()), 10);
        file_put_contents($filename, $fileContents);
        $this->ftpConnection->upload($filename, basename($filename));
        unlink($filename);
        $this->ftpConnection->download(basename($filename), $filename);
        $this->assertFileExists($filename);
        $this->assertEquals($fileContents, file_get_contents($filename));
        $this->ftpConnection->delete(basename($filename));
        unlink($filename);
    }

    public function testClose()
    {
        $ftpConnection = Factory::getConnection(Factory::TYPE_FTP, Env::FTP_HOST, Env::FTP_USER, Env::FTP_PASS);
        $ftpConnection->close();
    }

    public function testExec()
    {
        if(Env::FTP_EXEC) {
            $this->ftpConnection->exec('ls -la');
        }
    }

}
