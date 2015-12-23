<?php

use mazhuravlev\FileTransfer\ConnectionException;
use mazhuravlev\FileTransfer\Factory;
use mazhuravlev\FileTransfer\SFTPConnection;

class SFTPConnectionTest extends PHPUnit_Framework_TestCase
{

    /** @var  SFTPConnection */
    private $sftpConnection;

    public function setUp()
    {
        $this->sftpConnection = Factory::getConnection(Factory::TYPE_SFTP, Env::SFTP_HOST, Env::SFTP_USER, Env::SFTP_PASS);
    }

    public function testPwd()
    {
        $this->assertInternalType('string', $this->sftpConnection->pwd());
    }

    public function testCd()
    {
        $this->assertInstanceOf(SFTPConnection::class, $this->sftpConnection->cd(Env::SFTP_CD_DIR));
    }

    public function testCdPwd()
    {
        $this->sftpConnection->cd(Env::SFTP_CD_DIR);
        $this->assertStringEndsWith(Env::SFTP_CD_DIR, $this->sftpConnection->pwd());
    }

    public function testDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'd_');
        $this->assertFileExists($filename);
        try {
            $this->sftpConnection->download(Env::SFTP_DOWNLOAD_FILE, $filename);
        } catch (Exception $e) {
            $this->assertInstanceOf(ConnectionException::class, $e);
        }
        $this->sftpConnection->download(Env::SFTP_DOWNLOAD_FILE, $filename);
        unlink($filename);
        $this->sftpConnection->download(Env::SFTP_DOWNLOAD_FILE, $filename);
        $this->assertFileExists($filename);
        unlink($filename);
    }

    public function testUpload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        file_put_contents($filename, str_repeat(sha1(time()), 10));
        $this->sftpConnection->upload($filename, basename($filename));
        $this->sftpConnection->delete(basename($filename));
        unlink($filename);
    }

    public function testUploadDownload()
    {
        $filename = tempnam(Env::TMP_DIR, 'u_');
        $fileContents = str_repeat(sha1(time()), 10);
        file_put_contents($filename, $fileContents);
        $this->sftpConnection->upload($filename, basename($filename));
        unlink($filename);
        $this->sftpConnection->download(basename($filename), $filename);
        $this->assertFileExists($filename);
        $this->assertEquals($fileContents, file_get_contents($filename));
        $this->sftpConnection->delete(basename($filename));
        unlink($filename);
    }

    public function testClose()
    {
        $ftpConnection = Factory::getConnection(Factory::TYPE_FTP, Env::SFTP_HOST, Env::SFTP_USER, Env::SFTP_PASS);
        $ftpConnection->close();
    }

    public function testExec()
    {
            $this->assertInternalType('string', $this->sftpConnection->exec('ls'));
    }

}
