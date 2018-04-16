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
        $trimmedParams = array_map(function ($param) {
            return trim($param, "\\/");
        }, $directories);

        return "/" . implode("/", $trimmedParams);
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