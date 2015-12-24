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
        if (is_resource($this->resource)) {
            @ftp_close($this->resource);
        }
    }

    public function cd($directory)
    {
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if (@ftp_chdir($this->resource, $directory)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to change working directory');
        }
    }

    public function pwd()
    {
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if (false !== $pwd = @ftp_pwd($this->resource)) {
            return $pwd;
        } else {
            throw new ConnectionException('Unable to get working directory');
        }
    }

    public function upload($localFilename, $remoteFilename)
    {
        if (!file_exists($localFilename)) {
            throw new ConnectionException('File does not exist');
        }
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
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

    }

    public function download($remoteFilename, $localFilename, $overwrite = false)
    {
        if (!$overwrite and file_exists($localFilename)) {
            throw new ConnectionException('File exists and overwrite flag is not set');
        }
        if (!is_writable(dirname($localFilename))) {
            throw new ConnectionException('Local directory is not writable');
        }
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if (!@ftp_get($this->resource, $localFilename, $remoteFilename, FTP_BINARY)) {
            throw new ConnectionException('Unable to download file');
        }
        return $this;
    }

    public function close()
    {
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if ($this->resource and !@ftp_close($this->resource)) {
            throw new ConnectionException('Unable to close FTP connection');
        }
        $this->resource = null;
    }

    public function exec($command)
    {
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if (@ftp_exec($this->resource, $command)) {
            return '';
        } else {
            throw new ConnectionException('Unable to exec FTP command');
        }
    }

    public function delete($filename)
    {
        if (!is_resource($this->resource)) {
            throw new ConnectionException('Connection is not open');
        }
        if (@ftp_delete($this->resource, $filename)) {
            return $this;
        } else {
            throw new ConnectionException('Unable to delete file');
        }
    }
}