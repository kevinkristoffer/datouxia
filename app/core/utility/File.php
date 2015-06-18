<?php

class Puppy_Core_Utility_File
{

    /**
     * Get sub-directori(es) of a directory, not including the directory which
     * its name is one of ".", ".." or ".svn" or ".git"
     *
     * @param string $dir
     *            Path to the directory
     * @return array
     */
    public static function getSubDir($dir)
    {
        if (!file_exists($dir)) {
            return array();
        }

        $subDirs = array();
        $dirIterator = new DirectoryIterator ($dir);
        foreach ($dirIterator as $dir) {
            if ($dir->isDot() || !$dir->isDir()) {
                continue;
            }
            $dir = $dir->getFilename();
            if ($dir == '.svn' || $dir == '.git') {
                continue;
            }
            $subDirs [] = $dir;
        }
        return $subDirs;
    }

    /**
     * Create sub-directories of given directory
     *
     * @param string $root Path to root directory
     * @param string $path Relative path to new created directory in format a/b/c (on Linux)
     * or a\b\c (on Windows)
     */
    public static function createDirs($root, $path)
    {
        $root = rtrim($root, DS);
        $subDirs = explode(DS, $path);
        if ($subDirs == null) {
            return;
        }
        $currDir = $root;
        foreach ($subDirs as $dir) {
            $currDir = $currDir . DS . $dir;
            if (!file_exists($currDir)) {
                mkdir($currDir);
            }
        }
    }
}

?>