<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Admin\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Topazz\Controller\Controller;
use Topazz\Data\Filesystem;

class FilesController extends Controller {

    public function index(Request $request, Response $response): Response {
        $files = [
            "images" => [],
            "others" => []
        ];
        $filesystem = Filesystem::fromPath('storage/files');
        foreach ($filesystem->keys() as $filename) {
            if (!$filesystem->isDirectory($filename)) {
                $fileType = $filesystem->mimeType($filename);
                if (preg_match('/^image\/.*/', $fileType)) {
                    $files["images"] [] = [
                        "url" => "/storage/files/" . $filename,
                        "name" => $filename,
                        "type" => $fileType
                    ];
                } else {
                    $files["others"] []= [
                        "url" => "/storage/files/" . $filename,
                        "name" => $filename,
                        "type" => $filesystem->mimeType($filename)
                    ];
                }
            }
        }
        return $response->withJson($files);
    }

    public function upload(Request $request, Response $response): Response {
        $files = $request->getUploadedFiles();
        $filesystem = Filesystem::fromPath('storage/files');
        /** @var UploadedFile $file */
        foreach ($files as $file) {
            if(!$filesystem->has($file->getClientFilename())) {
                $file->moveTo("storage/files/" . $file->getClientFilename());
            }
        }
        return $this->index($request, $response);
    }

    public function remove(Request $request, Response $response, array $args): Response {
        $filename = $args['name'];
        $filesystem = Filesystem::fromPath('storage/files');
        if ($filesystem->has($filename)) {
            if ($filesystem->delete($filename)) {
                return $response->withStatus(200);
            }
            return $response->withStatus(500);
        }
        return $response->withStatus(404);
    }
}