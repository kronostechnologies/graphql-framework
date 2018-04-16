<?php


namespace Kronos\GraphQLFramework\Utils;


class DirectoryStringBuilder
{
    /**
     * Joins a list of directories names, ignoring slash issues. The returned directory
     * will not end with a trailing slash (unless it is root /).
     *
     * @param string[] ...$directories
     * @return string
     */
    public static function join(...$directories)
    {
        $trimmedDirs = array_map(function ($param) {
            return trim($param, "\\/");
        }, $directories);

        $retVal = "";

        foreach ($trimmedDirs as $trimmedDir)
        {
            if ($retVal === "" && strpos($trimmedDir, ".") === 0) {
                $retVal .= $trimmedDir;
            } else {
                $retVal .= "/" . $trimmedDir;
            }
        }

        return $retVal;
    }

    /**
     * Joins a directory path with the specified filename, ignoring slash issues.
     *
     * @param string $directory
     * @param string $filename
     * @return string
     */
    public static function joinFilename($directory, $filename)
    {
        $trimmedDirectory = rtrim($directory, "\\/");

        return $trimmedDirectory . "/" . $filename;
    }
}