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

    const PREFIX_IMAGE = "portal_qimob";

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
        $data = explode( ',', $request[$field] );
        $replace = $data[ 0 ];
        $replace = str_replace('data:image/', '', $replace);
        $replace = str_replace(';base64', '', $replace);
        $filename = self::PREFIX_IMAGE.md5(time().uniqid(rand(), true)) . '.'.$replace;
        $ifp = fopen( $path.'/'.$filename, 'wb' );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );
        $request[$field] = $filename;
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
            $filename = self::PREFIX_IMAGE.md5(time()) . '.' . $file->getClientOriginalExtension();
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