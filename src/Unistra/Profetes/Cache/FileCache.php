<?php

namespace Unistra\Profetes\Cache;

class FileCache implements Cache
{
    protected $directory;

    protected $ttl;

    public function __construct($directory, $ttl)
    {
        if ( ! is_dir($directory) && ! @mkdir($directory, 0777, true)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" does not exist and could not be created.',
                $directory
            ));
        }

        if ( ! is_writable($directory)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writable.',
                $directory
            ));
        }

        $this->directory = realpath($directory);
        $this->ttl = $ttl;
    }

    public function fetch($id, $ttl = null)
    {
        if (!is_null($ttl)) {
            $this->ttl = $ttl;
        }
        $fullFileName = $this->getFullPath($id);

        if (is_file($fullFileName) && is_readable($fullFileName)) {
            if ($this->isCacheStillValid($fullFileName)) {
                return file_get_contents($fullFileName);
            }
        }

        return '';
    }

    public function delete($id)
    {
        return @unlink($this->getFullPath($id));
    }

    public function save($id, $value)
    {
        $fn = $this->getFullPath($id);
        $filepath = pathinfo($fn, PATHINFO_DIRNAME);

        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }

        return file_put_contents($fn, $value) !== false;
    }

    private function isCacheStillValid($fullFileName)
    {
        return (time() - filemtime($fullFileName) < $this->ttl);
    }

    private function getFullPath($id)
    {
        $fullPath = $this->directory . "/" . $this->getFileName($id);

        return $fullPath;
    }

    private function getFileName($id)
    {
        $hash = sha1($id);
        $fileName = substr($hash, 0, 1) . "/" . substr($hash, 1);

        return $fileName;
    }
}
