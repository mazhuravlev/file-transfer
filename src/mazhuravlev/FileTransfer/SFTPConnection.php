<?php


namespace mazhuravlev\FileTransfer;


class SFTPConnection implements ConnectionInterface
{

    private $sftpResource, $sshResource;
    private $cdPath = null;

    public function __construct($sftpResource, $sshResource)
    {
        $this->sftpResource = $sftpResource;
        $this->sshResource = $sshResource;
    }

    public function __destruct()
    {
        if(is_resource($this->sshResource)) {
            @ssh2_exec($this->sshResource, 'exit');
        }
    }

    public function cd($directory)
    {
        $this->cdPath = $directory;
        return $this;
    }

    public function pwd()
    {
        $commands = [
            !is_null($this->cdPath) ? 'cd ' . $this->cdPath : null,
            'pwd'
        ];
        return $this->exec(implode('&&', array_filter($commands)));
    }

    public function upload($localFilename, $remoteFilename)
    {
        if (!file_exists($localFilename)) {
            throw new ConnectionException('File does not exist');
        }
        if(!is_resource($this->sftpResource)) {
            throw new ConnectionException('Connection is not open');
        }
        $scpResult = @ssh2_scp_send(
            $this->sshResource,
            $localFilename,
            $this->getRemotePath($remoteFilename)
        );
        if($scpResult) {
            return $this;
        } else {
            throw new ConnectionException('Unable to upload file');
        }
    }

    public function download($remoteFilename, $localFilename, $overwrite = false)
    {
        if(!$overwrite and file_exists($localFilename)) {
            throw new ConnectionException('File exists and overwrite flag is not set');
        }
        if (!is_writable(dirname($localFilename))) {
            throw new ConnectionException('Local directory is not writable');
        }
        if(!is_resource($this->sftpResource)) {
            throw new ConnectionException('Connection is not open');
        }
        $scpResult = @ssh2_scp_recv(
            $this->sshResource,
            $this->getRemotePath($remoteFilename),
            $localFilename
        );
        if($scpResult) {
            return $this;
        } else {
            throw new ConnectionException('Unable to download file');
        }
    }

    public function close()
    {
        if(is_resource($this->sshResource)) {
            @ssh2_exec($this->sshResource, 'exit');
            $this->sftpResource = null;
        } else {
            throw new ConnectionException('Connection is not open');
        }
    }

    public function delete($filename)
    {
        $sftpResult = @ssh2_sftp_unlink(
            $this->sftpResource,
            $this->getRemotePath($filename)
        );
        if($sftpResult) {
            return $this;
        } else {
            throw new ConnectionException('Unable to delete file');
        }
    }

    public function exec($command)
    {
        if(is_resource($this->sshResource)) {
            $stream = @ssh2_exec($this->sshResource, $command);
            if(false !== $stream) {
                stream_set_blocking($stream, true);
                $result = stream_get_contents(
                    @ssh2_fetch_stream($stream, SSH2_STREAM_STDIO)
                );
                if(false !== $result) {
                    return trim($result);
                } else {
                    throw new ConnectionException('Unable to read response stream');
                }
            } else {
                throw new ConnectionException('Unable to exec SFTP command');
            }
        } else {
            throw new ConnectionException('Connection is not open');
        }
    }

    private function getRemotePath($filename)
    {
        return !is_null($this->cdPath) ?
            $this->cdPath . '/' . $filename : $filename;
    }

}