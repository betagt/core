<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 12/01/2017
 * Time: 10:52
 */

namespace Modules\Core\Services;


use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class ImageUploadService
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function upload($field, $path, &$data)
    {
        $request = &$data;
        $file = $data[$field];
        if ($this->request->hasFile($field)) {
            if (!$file->isValid()) {
                Throw new InvalidParameterException('Ocorreu um erro ao realizar o upload');
            }
            $filename = md5(time().uniqid(rand(), true)) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
            $request['imagem'] = $filename;
        }
        return null;
    }

    private function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );
        return $output_file;
    }

    public function upload64($field, $path, &$data){
        $request = &$data;
        $filename = md5(time().uniqid(rand(), true)) . '.jpg';
        $ifp = fopen( $path.'/'.$filename, 'wb' );
        $data = explode( ',', $request['imagem'] );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );
        $request['imagem'] = $filename;
        return null;
    }

    public function upload_me($field, $path, $data)
    {
        $request = &$data;
        $file = $data[$field];
        if ($this->request->hasFile($field)) {
            if (!$file->isValid()) {
                Throw new InvalidParameterException('Ocorreu um erro ao realizar o upload');
            }
            $filename = md5(time()) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
            $request['imagem'] = $filename;
            return $request;
        }
        return null;
    }

    public function cropPhoto($path, $options, $target)
    {
        return \Image::make($path, $options)->save($target);
    }
}