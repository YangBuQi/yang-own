<?php


namespace App\Services;

//引入下载的资源包
include 'E:\phpstudy_pro\WWW\Laravel\Demo\vendor\phpqrcode\phpqrcode.php';

class QR
{
    /**
     *  $value //生成二位的的信息文本
     *  $outfile //表示是否输出二维码图片文件，默认否
     *  $errorCorrectionLevel //表示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
     *  $matrixPointSize //表示生成图片大小，默认是3
     *  $margin //表示二维码周围边框空白区域间距值
     *  $saveandprint //表示是否保存二维码并显示
     */
    public function qr()
    {
        //QRcode::png($value, $outfile = false, $errorCorrectionLevel = QR_ECLEVEL_L, $matrixPointSize = 3, $margin = 4, $saveandprint=false);

        $value = 'https://www.baidu.com/'; //二维码内容
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = 15;//生成图片大小
        //生成二维码图片
        \QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize,'2',true);
        $logo = 'C:\Users\user\Pictures\Camera Roll\Tmd.jpg';//准备好的logo图片
        $QR = 'qrcode.png';//已经生成的原始二维码图

        if ($logo !== FALSE) {
            /**
             * imagecreatefromstring
             * 参数:接受单个参数$filename，该参数保存图像的名称
             * 返回值：成功时此函数返回图像资源标识符，错误时返回FALSE
             */
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            /**
             * imagesx() 函数用于获取图像的宽度，单位为像素，返回值为整型
             * imagesy() 函数用于获取图像的高度，单位为像素，返回值为整型
             */
            // imagesx() 函数用于获取图像的宽度，单位为像素，返回值为整型
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;

            $from_width = ($QR_width - $logo_qr_width) / 2;
            /**
             * imagecopyresampled 图像处理函数
             * 参数：dst_image 目标图象连接资源
             *      src_image	源图象连接资源
             *      dst_x	目标 X 坐标点
             *      dst_y	目标 y 坐标点
             *      src_x	源的 X 坐标点
             *      src_y	源的 Y 坐标点
             *      dst_w	目标宽度
             *      dst_h	目标高度
             *      src_w	源图象的宽度
             *      src_h	源图象的高度
             * 返回值：成功时返回 TRUE， 或者在失败时返回 FALSE
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);  //重新组合图片并调整大小
        }

        $filename = time().mt_rand(111,999).'.png';

        //imagepng函数是PHP中的内置函数，用于向浏览器或文件显示图像。此函数的主要用途是在浏览器中查看图像，将任何其他图像类型转换为PNG并将过滤器应用于图像
        imagepng($QR, 'qr/'.$filename);  //输出图片，保存到本地

        echo '<img src="'.$filename.'">';
    }
}
