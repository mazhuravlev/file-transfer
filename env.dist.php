<?php

class Env
{
    const FTP_HOST = 'localhost';
    const FTP_USER = 'ftp';
    const FTP_PASS = 'ftp';
    const FTP_EXEC = false;
    const FTP_CD_DIR = 'test';
    const FTP_DOWNLOAD_FILE = 'test.txt';

    const SFTP_HOST = 'localhost';
    const SFTP_USER = 'ftp';
    const SFTP_PASS = 'ftp';
    const SFTP_CD_DIR = 'test';
    const SFTP_DOWNLOAD_FILE = 'test.txt';

    const TMP_DIR = __DIR__ . '/tests/tmpdir';
}
