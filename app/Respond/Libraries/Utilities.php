<?php

namespace App\Respond\Libraries;

use \Firebase\JWT\JWT;

class Utilities
{

    /**
     * Returns all HTML files for a given path
     *
     * @param {string} $path the recipient's email address
     * @return {Array} list of HTML fiels
     */
    public static function listFiles($dir, $id, $exts, $restrict = NULL)
    {

        $root = scandir($dir);

        if (!isset($result)) {
            $result = array();
        }

        foreach ($root as $value) {

            if ($value === '.' || $value === '..' || $value === '.htaccess') {
                continue;
            }

            if (is_file("$dir/$value")) {

                $file = "$dir/$value";

                $ext = pathinfo($file, PATHINFO_EXTENSION);

                if (in_array($ext, $exts) === TRUE) {

                    $paths = explode('sites/' . $id . '/', "$dir/$value");

                    $is_restricted = FALSE;

                    if ($restrict != NULL) {

                      foreach ($restrict as $item) {
                      
                          // TODO: MAKE SURE THE FILE DOES NOT START WITH A RESTRICTED PATH
                          if (substr($paths[1], 0, strlen($item)) === $item) {
                              $is_restricted = TRUE;
                          }

                      }

                    }

                    if ($is_restricted === FALSE) {
                        $result[] = $paths[1];
                    }


                } else {
                    continue;
                }

                continue;
            }

            foreach (Utilities::listFiles("$dir/$value", $id, $exts, $restrict) as $value) {
                $result[] = $value;
            }

        }

        return $result;

    }


    /**
     * Returns all routes
     *
     * @param {string} $path the recipient's email address
     * @return {Array} list of HTML fiels
     */
    public static function listRoutes($dir, $id)
    {

        $result = array();

        $restrict = array(
            'components',
            'css',
            'data',
            'files',
            'js',
            'locales',
            'fragments',
            'themes'
        );

        $cdir = scandir($dir);

        foreach ($cdir as $key => $value) {

            if (!in_array($value, array(
                ".",
                ".."
            ))) {

                if (is_dir("$dir/$value")) {

                    $paths = explode('sites/' . $id . '/', "$dir/$value");

                    $is_restricted = FALSE;

                    foreach ($restrict as $item) {

                        // TODO: MAKE SURE THE FILE DOES NOT START WITH A RESTRICTED PATH
                        if (substr($paths[1], 0, strlen($item)) === $item) {
                            $is_restricted = TRUE;
                        }

                    }

                    if ($is_restricted === FALSE) {

                        $arr = Utilities::listRoutes("$dir/$value", $id);

                        $result[] = '/' . $paths[1];
                        $result   = array_merge($result, $arr);

                    }
                }

            }

        }

        return $result;

    }


    /**
     * Sends an email from a specified file
     *
     * @param {string} $to the recipient's email address
     * @param {string} $from the sender's email address
     * @param {string} $fromName the name of the sender
     * @param {string} $subject the subject of the email
     * @param {Array} $replace an associative array of strings to replace
     * @param {string} $file the file to send
     * @return void
     */
    public static function sendEmailFromFile($to, $from, $fromName, $subject, $replace, $file)
    {

        if (file_exists($file)) {

            $content = file_get_contents($file);

            // walk through and replace values in associative array
            foreach ($replace as $key => &$value) {

                $content = str_replace($key, $value, $content);
                $subject = str_replace($key, $value, $subject);

            }

            // send email
            Utilities::sendEmail($to, $from, $fromName, $subject, $content);

            return true;

        } else {
            echo 'File does not exist=' . $file;

            return false;
        }

    }

    /**
     * Sends an email
     *
     * @param {string} $to the recipient's email address
     * @param {string} $from the sender's email address
     * @param {string} $fromName the name of the sender
     * @param {string} $subject the subject of the email
     * @param {string} $content the content of the email
     * @return void
     */
    public static function sendEmail($to, $from, $fromName, $subject, $content)
    {

        $mail = new \PHPMailer;

        // setup SMTP
        if (env('IS_SMTP') == true) {

            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = env('SMTP_HOST'); // Specify main and backup server

            if (env('SMTP_PORT')) {
                $mail->Port = env('SMTP_PORT');
            }

            $mail->SMTPAuth   = env('SMTP_AUTH'); // Enable SMTP authentication
            $mail->Username   = env('SMTP_USERNAME'); // SMTP username
            $mail->Password   = env('SMTP_PASSWORD'); // SMTP password
            $mail->SMTPSecure = env('SMTP_SECURE'); // Enable encryption, 'ssl' also accepted
            $mail->CharSet    = 'UTF-8';

        }

        $mail->From     = $from;
        $mail->FromName = $fromName;
        $mail->addAddress($to, '');
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = html_entity_decode($content, ENT_COMPAT, 'UTF-8');

        if (!$mail->send()) {
            return true;
        }

        return false;

    }

    /**
     * Creates a JWT token,
     * #ref: https://github.com/firebase/php-jwt, https://auth0.com/blog/2014/01/07/angularjs-authentication-with-cookies-vs-token/
     *
     * @param {string} $userId the id of the user
     * @param {string} $id the id of the site
     * @return void
     */
    public static function createJWTToken($email, $id)
    {

        // create token
        $token = array(
            'email' => $email,
            'id' => $id,
            'expires' => (strtotime('NOW') + (3 * 60 * 60)) // expires in an hour
        );

        // create JWT token, #ref: https://github.com/firebase/php-jwt
        $jwt_token = JWT::encode($token, env('JWT_KEY'));

        // return token
        return $jwt_token;
    }

    /**
     * Validates a JWT token,
     * #ref: https://github.com/firebase/php-jwt, https://auth0.com/blog/2014/01/07/angularjs-authentication-with-cookies-vs-token/
     *
     * @param {array} $auth the id of the user
     * @return void
     */
    public static function validateJWTToken($auth)
    {

        // locate token
        if (strpos($auth, 'Bearer') !== false) {

            $jwt = str_replace('Bearer ', '', $auth);

            try {

                // decode token
                $jwt_decoded = JWT::decode($jwt, env('JWT_KEY'), array(
                    'HS256'
                ));

                if ($jwt_decoded != NULL) {

                    // check to make sure the token has not expired
                    if (strtotime('NOW') < $jwt_decoded->expires) {
                        return $jwt_decoded;
                    } else {
                        return NULL;
                    }

                } else {
                    return NULL;
                }

                // return token
                return $jwt_decoded;

            }
            catch (Exception $e) {
                return NULL;
            }

        } else {
            return NULL;
        }

    }

    /**
     * Saves content to a file (creates the directory if needed)
     *
     * @param {string} $dir the directory
     * @param {string} $filename the filename of the new file
     * @param {string} $content the content of the new file
     * @return void
     */
    public static function saveContent($dir, $filename, $content)
    {
        $full = $dir . $filename;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $fp = @fopen($full, 'w'); // Generate a new cache file
        @fwrite($fp, $content); // save the contents of output buffer to the file
        @fclose($fp);
    }

    /**
     * Copies a directory
     *
     * @param {string} $src the source
     * @param {string} $dst the destination
     * @return void
     */
    public static function copyDirectory($src, $dst)
    {
        $dir = opendir($src);

        if (!file_exists($dst)) {
            mkdir($dst, 0777, true);
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    Utilities::copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }


    /**
     *  Creates a thumbnail
     *
     * @param {Site} $site
     * @param {string} $filename
     * @param {string} $image path to the image
     * @return {array}
     */
    public static function createThumb($site, $image, $filename)
    {

        $dir = app()->basePath().'/public/sites/'.$site->id.'/files/thumbs';

        // set thumb size
        $target_w = env('THUMB_MAX_WIDTH');
        $target_h = env('THUMB_MAX_HEIGHT');

        list($curr_w, $curr_h, $type, $attr) = Utilities::getImageInfo($image);

        $ext = 'jpg';

        switch ($type) { // create image
            case IMAGETYPE_JPEG:
                $ext = 'jpg';
                break;
            case IMAGETYPE_PNG:
                $ext = 'png';
                break;
            case IMAGETYPE_GIF:
                $ext = 'gif';
                break;
            case 'image/svg+xml':
                $ext = 'svg';
                break;
            default:
                return false;
        }

        $scale_h = $target_h / $curr_h;
        $scale_w = $target_w / $curr_w;

        $factor_x = ($curr_w / $target_w);
        $factor_y = ($curr_h / $target_h);

        if ($factor_x > $factor_y) {
            $factor = $factor_y;
        } else {
            $factor = $factor_x;
        }

        $up_w = ceil($target_w * $factor);
        $up_h = ceil($target_h * $factor);

        $x_start = ceil(($curr_w - $up_w) / 2);
        $y_start = ceil(($curr_h - $up_h) / 2);

        switch ($type) { // create image
            case IMAGETYPE_JPEG:
                $n_img = imagecreatefromjpeg($image);
                break;
            case IMAGETYPE_PNG:
                $n_img = imagecreatefrompng($image);
                break;
            case IMAGETYPE_GIF:
                $n_img = imagecreatefromgif($image);
                break;
            case 'image/svg+xml':
                break;
            default:
                return false;
        }

        $dst_img = ImageCreateTrueColor($target_w, $target_h);
        switch ($type) { // fix for transparency issues
            case IMAGETYPE_PNG:
                imagealphablending($dst_img, true);
                imagesavealpha($dst_img, true);
                $transparent_color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
                imagefill($dst_img, 0, 0, $transparent_color);
                break;
            case IMAGETYPE_GIF:
                $transparency_index = imagecolortransparent($dst_img);
                if ($transparency_index >= 0) {
                    $transparent_color  = imagecolorsforindex($dst_img, $transparency_index);
                    $transparency_index = imagecolorallocate($dst_img, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                    imagefill($dst_img, 0, 0, $transparency_index);
                    imagecolortransparent($dst_img, $transparency_index);
                }

                break;
            default:
                break;
        }


        if ($type != 'image/svg+xml') {
            imagecopyresampled($dst_img, $n_img, 0, 0, $x_start, $y_start, $target_w, $target_h, $up_w, $up_h);
        }


        //return $dst_img;
        $full = $dir . '/' . $filename;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        switch ($ext) {
            case 'jpg': {

                imagejpeg($dst_img, $full, 100);

                break;
            }
            case 'png': {

                // save file locally
                imagepng($dst_img, $full);

                break;
            }
            case 'gif': {

                // save file locally
                imagegif($dst_img, $full);

                break;
            }
            case 'svg': {

                // save file locally
                copy($image, $full);

                break;
            }
            default:
                return false;

                return true;
        }

    }

    /**
     *  Gets image info
     *
     * @param {string} $file path to the image
     * @return {array}
     */
    public static function getImageInfo($file)
    {

        list($width, $height, $type, $attr) = getimagesize($file);

        if (empty($type)) { // we try for svg
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fi    = finfo_file($finfo, $file);
            if ($fi == 'image/svg+xml') { // for SVG files which getimagesize returns empty values
                $xmlget        = simplexml_load_file($file);
                $xmlattributes = $xmlget->attributes();
                $width         = (int) $xmlattributes->width; // this is approximate
                $height        = (int) $xmlattributes->height; // this is approximate
                $type          = 'image/svg+xml';
                $attr          = '';
            }
        }

        return array(
            $width,
            $height,
            $type,
            $attr
        );
    }

}