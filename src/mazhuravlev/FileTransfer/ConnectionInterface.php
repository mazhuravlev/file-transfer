<?php


namespace mazhuravlev\FileTransfer;


interface ConnectionInterface
{

    /**
     * @return ConnectionInterface
     */
    public function cd($directory);
    /**
     * @return string
     */
    public function pwd();
    /**
     * @return ConnectionInterface
     */
    public function upload($localFilename, $remoteFilename);
    /**
     * @return ConnectionInterface
     */
    public function download($remoteFilename, $localFilename, $overwrite = false);
    /**
     * @return string
     */
    public function exec($command);
    public function close();
    /**
     * @return ConnectionInterface
     */
    public function delete($remoteFilename);

}