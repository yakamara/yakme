<?php
namespace Yakme;

class Download
{
    public
        $path,
        $filename;

    public function __construct($filename, $dir = '')
    {
        $this->path = null;
        if (file_exists($dir . $filename)) {
            $this->path = $dir . $filename;
        } else {
            $media = \rex_media::get($filename);
            if($media) {
                $this->path = \rex_path::media($media->getFileName());
            }
        }
        //dump([$this->path, $filename, $dir, $dir . $filename]);
        //exit();
        $this->filename = $filename;
    }

    public function force()
    {
        \rex_response::cleanOutputBuffers();

        if (!file_exists($this->path)) {
            header('HTTP/1.1 ' . \rex_response::HTTP_NOT_FOUND);
            exit;
        }

        \rex_response::sendContentType('application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $this->filename . '"');

        \rex_response::sendLastModified(filemtime($this->path));

        header('HTTP/1.1 ' . \rex_response::HTTP_OK);
        \rex_response::sendCacheControl('max-age=3600, must-revalidate, proxy-revalidate, private');

        // content length schicken, damit der browser einen ladebalken anzeigen kann
        if (!ini_get('zlib.output_compression')) {
            header('Content-Length: ' . filesize($this->path));
        }

        readfile($this->path);
        exit;
    }
}
