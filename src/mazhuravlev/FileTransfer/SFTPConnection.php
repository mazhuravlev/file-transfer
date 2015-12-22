<?php


namespace mazhuravlev\FileTransfer;


class SFTPConnection implements ConnectionInterface
{

    private $resource;

    public function __construct($sftpResource)
    {
        $this->resource = $sftpResource;
    }

    public function cd($directory)
    {
        // TODO: Implement cd() method.
    }

    public function pwd()
    {
        // TODO: Implement pwd() method.
    }

    public function upload($localFilename, $remoteFilename)
    {
        // TODO: Implement upload() method.
    }

    public function download($remoteFileName, $localFilename)
    {
        // TODO: Implement download() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function exec($command)
    {
        // TODO: Implement exec() method.
    }
}