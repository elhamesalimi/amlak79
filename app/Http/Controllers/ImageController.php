<?php

namespace App\Http\Controllers;

use App\Estate;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use yasmuru\LaravelTinify\Facades\Tinify;

class ImageController extends Controller
{
    private $photos_path;
    private $thumbs160x160_path;
    private $thumbs110x62_path;
    private $thumbs600x450_path;
    private $thumbs_width;
    private $base_width;
    private $thumbs_height;
    private $base_height;
    private $base_path;
    private $quality;

    public function __construct()
    {
        $this->base_path = '/public_data';
        $this->photos_path = '/images/orginal';
        $this->thumbs160x160_path = $this->base_path . '/images/thumbs160x160/';
        $this->thumbs110x62_path = $this->base_path . '/images/thumbs110x62/';
        $this->thumbs600x450_path = $this->base_path . '/images/thumbs600x450/';
        $this->base_width = 700;
        $this->base_height = 525;
        $this->thumbs_width = 200;
        $this->thumbs_height = 200;
        $this->quality = 72;
    }

    public function setEstatePhotos()
    {
        $uploadedImages = Image::where('estate_id', request()->estate_id)->select('id')->get();

        $uploadedImagesIds = [];
        foreach ($uploadedImages as $uploadedImage) {
            $uploadedImagesIds[] = $uploadedImage->id;
        }
        if (!empty($uploadedImages)) {
            foreach ($uploadedImagesIds as $uploadedImagesId)
                if (!in_array($uploadedImagesId, request()->imagesId)) {
                    Image::find($uploadedImagesId)->delete();
                }
            foreach (request()->imagesId as $id)
                if (!in_array($id, $uploadedImagesIds)) {
                    $image = Image::find($id);
                    $image->estate_id = request()->estate_id;
                    $image->save();
                }
        } else {

            foreach (request()->imagesId as $image_id) {
                $image = Image::find($image_id);
                $image->estate_id = request()->estate_id;
                $image->save();
            }
        }
        return response('estateId add to Image  successfully', 200);
    }

    public function uploadPhotos(Request $request)
    {
        if (request()->file('file') instanceof \Symfony\Component\HttpFoundation\File\File) {
            $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG", "PNG", "GIF", "JPE", "jpe");

            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ((($_FILES["file"]["type"] == "image/gif")
                    || ($_FILES["file"]["type"] == "image/jpeg")
                    || ($_FILES["file"]["type"] == "image/JPG")
                    || ($_FILES["file"]["type"] == "image/jpg")
                    || ($_FILES["file"]["type"] == "image/pjpeg")
                    || ($_FILES["file"]["type"] == "image/x-png")
                    || ($_FILES["file"]["type"] == "image/png"))
                && ($_FILES["file"]["size"] < 10 * 1024 * 1024 * 1024)
                && in_array($extension, $allowedExts)) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
//                    if (file_exists("wp-dcontent/uploads/2015/10/" . $_FILES["file"]["name"])){
//                        echo $_FILES["file"]["name"] . " already exists. ";
                } else {

                    $name = str_random(25) . '.' . $extension;
                    $path = request()->file->storeAs($this->photos_path, $name);
                    //indicate which file to resize (can be any type jpg/JPG/png/gif/etc...)
//                $file = 'wp-content/uploads/2015/10/' . $name;

                    //indicate the path and name for the new resized file
//                $resizedFile = 'thumbnail/wp-content/uploads/2015/10/' . $name;
//                $resizedCover = 'cover/wp-content/uploads/2015/10/' . $name;

                    //call the function (when passing path to pic)
                    $file = $this->base_path . '/' . $path;
                    $this->smart_resize_image($file, null, $this->base_width, $this->base_height, false, $this->thumbs600x450_path . $name, false, false, $this->quality);
                    $this->smart_resize_image($file, null, $this->thumbs_width, $this->thumbs_height, false, $this->thumbs160x160_path . $name, false, false, $this->quality);
                    $this->smart_resize_image($file, null, 110, 62, false, $this->thumbs110x62_path . $name, false, false, $this->quality);
                    $photo = $this->thumbs600x450_path . $name;
                    $this->addWaterMark($photo);
                }
//            if (empty($img1) && empty($img2) && empty($img3) && empty($img4)) {
//                $aks = "ندارد";
//            } else {
//                $aks = "دارد";
//            }
            }
            $image = new Image();
            $image->uri = $name;
            $image->estate_id = $request->estate_id;
            $image->save();
            return response($image->id);

            $file = $request->file('file');
            $ext = $file->extension();
            $name = str_random(20) . '.' . $ext;
            list($width, $height) = getimagesize($file);
            $path = Storage::disk('public')->putFileAs(
                'uploads', $file, $name
            );

            if ($path) {
                $create = Auth::user()->photos()->create([
                    'uri' => $path,
                    'public' => false,
                    'height' => $height,
                    'width' => $width
                ]);

                if ($create) {
                    return response()->json([
                        'uploaded' => true
                    ]);
                }
            }
        }
        return response()->json([
            'uploaded' => false
        ]);
    }

    static function smart_resize_image($file,
                                       $string = null,
                                       $width = 0,
                                       $height = 0,
                                       $proportional = false,
                                       $output = 'file',
                                       $delete_original = true,
                                       $use_linux_commands = false,
                                       $quality = 100
    )
    {
        $file = public_path($file);
        $output = public_path($output);
        if ($height <= 0 && $width <= 0) return false;
        if ($file === null && $string === null) return false;
        # Setting defaults and meta
        $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;
        # Calculating proportionality
        if ($proportional) {
            if ($width == 0) $factor = $height / $height_old;
            elseif ($height == 0) $factor = $width / $width_old;
            else                    $factor = min($width / $width_old, $height / $height_old);
            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width = ($width <= 0) ? $width_old : $width;
            $final_height = ($height <= 0) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
//        }
            # Loading image to memory according to type
            if ($info[2] === 2) {
                $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
            }

            # This is the resizing/resampling/transparency-preserving magic
            $image_resized = imagecreatetruecolor($final_width, $final_height);
//        if (($info[2] == 1) || ($info[2] == 3)) {
//            $transparency = imagecolortransparent($image);
//            $palletsize = imagecolorstotal($image);
//            if ($transparency >= 0 && $transparency < $palletsize) {
//                $transparent_color = imagecolorsforindex($image, $transparency);
//                $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
//                imagefill($image_resized, 0, 0, $transparency);
//                imagecolortransparent($image_resized, $transparency);
//            } elseif ($info[2] == 3) {
//                imagealphablending($image_resized, false);
//                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
//                imagefill($image_resized, 0, 0, $color);
//                imagesavealpha($image_resized, true);
//            }
//        }
            imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

            # Taking care of original, if needed
            if ($delete_original) {
                if ($use_linux_commands) exec('rm ' . $file);
                else @unlink($file);
            }
            # Preparing a method of providing result
            switch (strtolower($output)) {
                case 'browser':
                    $mime = image_type_to_mime_type($info[2]);
                    header("Content-type: $mime");
                    $output = NULL;
                    break;
                case 'file':
                    $output = $file;
                    break;
                case 'return':
                    return $image_resized;
                    break;
                default:
                    break;
            }

            # Writing image according to type to the output destination and image quality
            if ($info[2] === 2) {
                imagejpeg($image_resized, $output, $quality);
            }
            return true;
        }
    }

    static function addWaterMark($photo)
    {
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng(public_path('/images/watermark/stamp.png'));
        $im = imagecreatefromjpeg(public_path($photo));

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        // Copy the stamp image onto our photo using the margin offsets and the photo
        // width to calculate positioning of the stamp.
        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        //insert text watermark
        $textcolor = imagecolorallocatealpha($im, 255, 255, 255, 85);
        $font_file = public_path('/asset/fonts/IRANSans.ttf');
        $custom_text = "79";
        imagettftext($im, 100, -2, 270, 318, $textcolor, $font_file, $custom_text);
//
        imagejpeg($im, public_path($photo));
        // Output and free memory
        header('Content-type: image/png');
//        imagepng($im , public_path($photo));
        imagedestroy($im);
    }

    public function destroyPhoto(Request $request)
    {
        $id = $request->id;
        $image = Image::find($id);
        if (!$image) {
            return response()->json('Image Not Found', 200);
        }
        $name = $image->uri;
        $file_path = public_path($this->base_path . $this->photos_path . '/' . $name);
        $thumbs110x62_path = public_path($this->thumbs110x62_path . '/' . $name);
        $thumbs160x160_path = public_path($this->thumbs160x160_path . '/' . $name);
        $thumbs600x450_path = public_path($this->thumbs600x450_path . '/' . $name);

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        if (file_exists($thumbs110x62_path)) {
            unlink($thumbs110x62_path);
        }
        if (file_exists($thumbs160x160_path)) {
            unlink($thumbs160x160_path);
        }
        if (file_exists($thumbs600x450_path)) {
            unlink($thumbs600x450_path);
        }

        $image->delete();

        return response()->json(['message' => 'File successfully delete'], 200);
    }

    public function getEstateImages($id)
    {
        $imageAnswer = [];

        $images = Image::where('estate_id', $id)->get();

        foreach ($images as $image) {
            $imageAnswer[] = [
                'id' => $image->id,
                'thumb' => $this->thumbs160x160_path . $image->uri,
                'name' => $image->uri,
                'size' => File::size(public_path($this->thumbs600x450_path . $image->uri))
            ];
        }

        return response()->json([
            'images' => $imageAnswer
        ]);
    }

    public function hidePhoto()
    {
        $image = Image::find(request()->id);
        $image->status = !$image->status;
        $image->save();
        return response(['status' => $image->status], 200);
    }

    public function setAvatar()
    {
        $images = Estate::find(request()->estate_id)->images()->update(['avatar'=>false]);

        $image = Image::find(request()->id);
        $image->avatar = true;
        $image->save();
        return response('set Photo as Avatat successfully', 200);
    }
}
