<?php


namespace mazhuravlev\FileTransfer;


interface ConnectionInterface
{

    /**
     * @return ConnectionInterface
     */
    public function cd($directory);
    public function pwd();
    public function upload($file);
    public function download($remoteFilename, $filename);
    public function close();

}