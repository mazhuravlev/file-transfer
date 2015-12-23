<?php


namespace mazhuravlev\FileTransfer;


class SFTPConnection implements ConnectionInterface
{

    private $sftpResource, $sshResource;

    public function __construct($sftpResource, $sshResource)
    {
        $this->sftpResource = $sftpResource;
        $this->sshResource = $sshResource;
    }

    public function __destruct()
    {
        if(is_resource($this->sshResource)) {
            ssh2_exec($this->sshResource, 'exit');
        }
    }

    public function cd($directory)
    {
        $this->exec("cd $directory");
        return $this;
    }

    public function pwd()
    {
        return $this->exec('pwd');
    }

    public function upload($localFilename, $remoteFilename)
    {
        if(!is_resource($this->sftpResource)) {
            throw new ConnectionException('Connection is not open');
        }
    }

    public function download($remoteFileName, $localFilename)
    {
        if(!is_resource($this->sftpResource)) {
            throw new ConnectionException('Connection is not open');
        }
    }

    public function close()
    {
        if(is_resource($this->sshResource)) {
            ssh2_exec($this->sshResource, 'exit');
            $this->sftpResource = null;
        } else {
            throw new ConnectionException('Connection is not open');
        }
    }

    public function delete($filename)
    {
        if(ssh2_sftp_unlink($this->sftpResource, $filename)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to delete file');
        }
    }

    public function exec($command)
    {
        if(is_resource($this->sshResource)) {
            $stream = ssh2_exec($this->sshResource, $command);
            if(false !== $stream) {
                return fgets($stream);
            } else {
                throw new ConnectionException('Unable to exec SFTP command');
            }
        } else {
            throw new ConnectionException('Connection is not open');
        }
    }
}