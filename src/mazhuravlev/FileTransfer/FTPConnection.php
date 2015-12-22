<?php


namespace mazhuravlev\FileTransfer;


class FTPConnection implements ConnectionInterface
{

    private $resource;

    public function __construct($ftpResource)
    {
        $this->resource = $ftpResource;
    }

    public function __destruct()
    {
        if (!is_null($this->resource)) {
            ftp_close($this->resource);
        }
    }

    public function cd($directory)
    {
        if (@ftp_chdir($this->resource, $directory)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to change working directory');
        }
    }

    public function pwd()
    {
        if (false !== $pwd = ftp_pwd($this->resource)) {
            return $pwd;
        } else {
            throw new ConnectionException('Unable to get working directory');
        }
    }

    public function upload($localFilename, $remoteFilename)
    {
        if (file_exists($localFilename)) {
            $putResult = @ftp_put(
                $this->resource,
                is_null($remoteFilename) ? $localFilename : $remoteFilename,
                $localFilename, FTP_BINARY
            );
            if ($putResult) {
                return $this;
            } else {
                throw new ConnectionException('Unable to upload file');
            }
        } else {
            throw new ConnectionException('File does not exist');
        }
    }

    public function download($remoteFilename, $localFilename, $rewrite = false)
    {
        if (is_null($localFilename)) {
            $localFilename = $remoteFilename;
        }
        if (!$rewrite and file_exists($localFilename)) {
            throw new ConnectionException('File exists and rewrite flag is not set');
        }
        if (!is_writable(dirname($localFilename))) {
            throw new ConnectionException('Local directory is not writable');
        }
        if (!ftp_get($this->resource, $localFilename, $remoteFilename, FTP_BINARY)) {
            throw new ConnectionException('Unable to download file');
        }
        return $this;
    }

    public function close()
    {
        if ($this->resource and !ftp_close($this->resource)) {
            throw new ConnectionException('Unable to close FTP connection');
        }
        $this->resource = null;
    }

    public function exec($command)
    {
        if(@ftp_exec($this->resource, $command)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to exec FTP command');
        }
    }

    public function delete($filename)
    {
        if(ftp_delete($this->resource, $filename)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to delete file');
        }
    }
}