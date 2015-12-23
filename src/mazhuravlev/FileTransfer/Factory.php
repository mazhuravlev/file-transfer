<?php


namespace mazhuravlev\FileTransfer;


class Factory
{

    const TYPE_FTP = 'ftp';
    const TYPE_SFTP = 'sftp';

    const DEFAULT_FTP_PORT = 21;
    const DEFAULT_SFTP_PORT = 22;

    /**
     * @return ConnectionInterface
     */
    public static function getConnection($type, $host, $username, $password, $port = null)
    {
        switch($type) {
            case self::TYPE_FTP:
                return self::getFTPConnection($host, $username, $password, $port);
                break;
            case self::TYPE_SFTP:
                return self::getSFTPConnection($host, $username, $password, $port);
                break;
            default:
                throw new \InvalidArgumentException('Invalid connection type');
        }
    }

    private static function getFTPConnection($host, $username, $password, $port)
    {
        $ftpResource = @ftp_connect($host, is_null($port) ? self::DEFAULT_FTP_PORT : $port);
        if($ftpResource) {
            if(ftp_login($ftpResource, $username, $password)) {
                return new FTPConnection($ftpResource);
            } else {
                throw new \RuntimeException('Unable to login to FTP server');
            }
        } else {
            throw new \RuntimeException('Unable to connect to FTP server');
        }
    }

    private static function getSFTPConnection($host, $username, $password, $port)
    {
        $sshResource = ssh2_connect($host, is_null($port) ? self::DEFAULT_SFTP_PORT : $port);
        if($sshResource) {
            if(ssh2_auth_password($sshResource, $username, $password)) {
                if($sftpResource = ssh2_sftp($sshResource)) {
                    return new SFTPConnection($sftpResource, $sshResource);
                } else {
                    throw new \RuntimeException('Unable to initialize SFTP subsystem');
                }
            } else {
                throw new \RuntimeException('Unable to login to SFTP server');
            }
        } else {
            throw new \RuntimeException('Unable to connect to SFTP server');
        }
    }

}