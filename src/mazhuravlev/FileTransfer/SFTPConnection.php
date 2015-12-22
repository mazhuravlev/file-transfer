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

    public function upload($file)
    {
        // TODO: Implement upload() method.
    }

    public function download($remoteFileName, $filename)
    {
        // TODO: Implement download() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}