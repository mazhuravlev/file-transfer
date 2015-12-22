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
        ftp_close($this->resource);
    }

    public function cd($directory)
    {
        if(!@ftp_chdir($this->resource, $directory)) {
            throw new ConnectionException('Unable to change working directory');
        }
        return $this;
    }

    public function pwd()
    {
        if(false !== $pwd = ftp_pwd($this->resource)) {
            return $pwd;
        } else {
            throw new ConnectionException('Unable to get working directory');
        }
    }

    public function upload($filename, $remoteFilename = null)
    {
        if(file_exists($filename)) {
            $putResult = ftp_put(
                $this->resource,
                is_null($remoteFilename) ? $filename : $remoteFilename,
                $filename, FTP_BINARY
            );
            if($putResult) {
                return $this;
            } else {
                throw new ConnectionException('Unable to upload file');
            }
        } else {
            throw new ConnectionException('File does not exist');
        }
    }

    public function download($remoteFilename, $filename, $rewrite = false)
    {
        if(!$rewrite and file_exists($filename)) {
            throw new ConnectionException('File exists and rewrite flag is not set');
        }
        if(!is_writable($filename)) {
            throw new ConnectionException('File is not writable');
        }
        if(!ftp_get($this->resource, $filename, $remoteFilename, FTP_BINARY)) {
            throw new ConnectionException('Unable to download file');
        }
        return $this;
    }

    public function close()
    {
        if(!ftp_close($this->resource)) {
            throw new ConnectionException('Unable to close FTP connection');
        }
    }

}