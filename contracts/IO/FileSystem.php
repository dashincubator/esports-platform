<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Local FileSystem
 *
 */

namespace Contracts\IO;

use DateTime;

interface FileSystem
{

    /**
     *  Appends Data To A File
     *
     *  @param string $path File Path To Append To
     *  @param mixed $data Value To Append To File
     *  @return int|bool Number Of Bytes Written If Successful Otherwise False
     *  @throws Exception Thrown If There Was A Problem Appending To The File
     */
    public function append(string $path, $data) : int;


    /**
     *  Change File Mode Of Path
     *
     *  @param string $path Path To Modify
     *  @param int $mode Permissions As Octal Value
     *  @return bool True If Successful, Otherwise False
     */
    public function chmod(string $path, int $mode) : bool;


    /**
     *  Copy Directory From One Path To Another
     *
     *  @param string $from Path To Copy From
     *  @param string $to Path To Copy To
     *  @param int $flags File Permissions To Use On New Directory(ies)
     *  @return bool True If Successful, Otherwise False
     */
    public function copyDirectory(string $from, string $to, int $flags = FilesystemIterator::SKIP_DOTS) : bool;


    /**
     *  Copies A File To A New Location
     *
     *  @param string $from Path To Copy From
     *  @param string $to Path To Copy To
     *  @return bool True If Successful, Otherwise False
     */
    public function copyFile(string $from, string $to) : bool;


    /**
     *  Delete Directory
     *
     *  @param string $path Path Of Directory To Delete
     *  @param boolean $deleteDirectoryStructure True If Directory In "$path" Should Be Deleted, Otherwise False
     *  @return bool True If Successful, Otherwise False
     */
    public function deleteDirectory(string $path, bool $deleteDirectoryStructure = false): bool;


    /**
     *  Deletes A File
     *
     *  @param string $path Directory/File To Delete
     *  @return bool True If Successful, Otherwise False
     */
    public function deleteFile(string $path) : bool;


    /**
     *  Checks If A File Or Directory Exists
     *
     *  @param string $path Path To Check
     *  @return bool True If Exists, Otherwise False
     */
    public function exists(string $path) : bool;


    /**
     *  @param string $path File Or Directory Path
     *  @return int Permissions As Octal Value
     */
    public function getChmod(string $path) : int;


    /**
     *  Get Paths Of All Directories Within '$path'
     *
     *  @param string $path Directory To Read From
     *  @param boolean $isRecursive If True Search Subdirectories, Otherwise False
     *  @return array Directory Paths Found
     */
    public function getDirectories(string $path, bool $isRecursive = false) : array;


    /**
     *  @param string $path File Path
     *  @return string Directory Path
     *  @throws Exception Thrown If The File Does Not Exist
     */
    public function getDirectoryName(string $path): string;


    /**
     *  Returns Extension Of A File
     *
     *  @param string $path Path To Check
     *  @return string Extension Of The File
     *  @throws Exception Thrown If The File Does Not Exist
     */
    public function getExtension(string $path) : string;


    /**
     *  Returns File Name Of A File
     *
     *  @param string $path Path To Check
     *  @return string File Name
     *  @throws Exception Thrown If The File Does Not Exist
     */
    public function getFileName(string $path) : string;


    /**
     *  Returns Size Of A File
     *
     *  @param string $path Path To Check
     *  @return int Number Of Bytes The File Has
     *  @throws Exception Thrown If The File Does Not Exist
     */
    public function getFileSize(string $path) : int;


    /**
     *  Get Paths Of All Files Within '$path'
     *
     *  @param string $path Directory To Read File Paths From
     *  @param boolean $isRecursive If True Search Subdirectories, Otherwise False
     *  @return array File Paths Found In Directory
     */
    public function getFiles(string $path, bool $isRecursive = false) : array;


    /**
     *  Returns Last Modified Time
     *
     *  @param string $path Path To Check
     *  @return DateTime Last Modified Time In Unix Timestamp
     *  @throws Exception Thrown If File Was Not Found Or If The Modified Time Was Not Readable
     */
    public function getLastModified(string $path) : int;


    /**
     *  Finds Files That Match A Glob Pattern
     *
     *  @link http://php.net/manual/function.glob.php
     *
     *  @param string $pattern Pattern To Match
     *  @param int $flags Glob Flags To Use
     *  @return array List Of Matched Files
     *  @throws Exception Thrown Search Failed
     */
    public function glob(string $pattern, int $flags = 0) : array;


    /**
     *  Include File ( Executes PHP Code )
     *
     *  @param string $path File Path To Include
     *  @param array  $data Extracted To Be Used By File Contents
     *  @return mixed
     */
    public function include(string $path, array $data = []);


    /**
     *  Returns Whether Or Not A Path Points To A File
     *
     *  @param string $path Path To Check
     *  @return bool True If Path Is A Directory, Otherwise False
     */
    public function isDirectory(string $path) : bool;


    /**
     *  Returns Whether Or Not A Path Points To A File
     *
     *  @param string $path Path To Check
     *  @return bool True If Path Points To A File, Otherwise False
     */
    public function isFile(string $path) : bool;


    /**
     *  Returns Whether Or Not A Path Is Readable
     *
     *  @param string $path Path To Check
     *  @return bool True If Readable, Otherwise False
     */
    public function isReadable(string $path) : bool;


    /**
    *  Returns Whether Or Not A Path Is Writable
    *
    *  @param string $path Path To Check
    *  @return bool True If Writable, Otherwise False
    */
    public function isWritable(string $path) : bool;


    /**
     *  Make A Directory At The Input Path
     *
     *  @param string $path Path To Create
     *  @param integer $mode chmod Permissions
     *  @param boolean $isRecursive Whether Or Not We Create Nested Directories
     *  @return bool True If Successful, Otherwise False
     */
    public function makeDirectory(string $path, int $mode = 0777, bool $isRecursive = false) : bool;


    /**
     *  Moves A File From '$from' Location To '$to' Location
     *
     *  @param string $from Path To Move From
     *  @param string $to Path To Move To
     *  @return bool True If Successful, Otherwise False
     */
    public function move(string $from, string $to) : bool;


    /**
     *  Reads Contents Of A File
     *
     *  @param string $path Path Of The File To Read
     *  @return string Contents Of The File
     *  @throws Exception Thrown If The Path Was Not Valid
     */
    public function read(string $path) : string;


    /**
     *  Require File ( Executes PHP Code )
     *
     *  @param string $path File Path To Require
     *  @param array  $data Extracted To Be Used By File Contents
     *  @return mixed
     */
    public function require(string $path, array $data = []);


    /**
     *  Writes Data To A File
     *
     *  @param string $path File Path To Write To
     *  @param mixed $data Value To Write To File
     *  @param int $flags PHP's 'file_put_contents()' Flags
     *  @return int|bool Number Of Bytes Written If Successful Otherwise False
     *  @throws Exception Thrown If There Was A Problem Writing To The File
     */
    public function write(string $path, $data, int $flags = 0) : int;
}
