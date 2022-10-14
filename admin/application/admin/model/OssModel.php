<?php
namespace app\admin\model;
use think\Model;
use think\facade\Session;
use app\admin\model\UserModel;
use OSS\OssClient;
use OSS\Core\OssException;
class OSSModel extends Model
{
    // 单图上传
    public function doupload($path)
    {
        // 配置文件
        $accessKeyId = "";
        $accessKeySecret = "";
        $endpoint = "";
        $bucket = "";
        $file = request()->file('file');
        // 文件名称生成
        $file_name = date('YmdHis',time()).uniqid().'.'.pathinfo($file->getInfo()['name'],PATHINFO_EXTENSION);
        $url =  $path.$file_name;
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $result =$ossClient->uploadFile($bucket,$url,$file->getInfo()['tmp_name']);
            if (isset($result['info']['http_code']) AND $result['info']['http_code']==200) {
                json_exit(200,"上传成功",$result['info']['url']);
            }else{
                json_exit(401,"上传错误");
            }
        } catch(OssException $e) {
            $msg = $e->getMessage();
            json_exit(401,$msg);
        }
    } 
    
        //图片上传本地
    public function upload_img($ImgPath)
    {
        $file = request()->file('file'); // 获取上传的文件
        if ($file == null) {
            exit(json_encode(array('code' => 1, 'msg' => '未上传图片', 'data' => $_FILES)));
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if (!in_array($extension, array("gif", "jpeg", "jpg", "png"))) {
            exit(json_encode(['code' => 1, 'msg' => '上传图片不合法']));
        }
        $path = $ImgPath;
        $info = $file->move($path); // 移动文件到指定目录 没有则创建

        $img = $info->getSaveName();
        json_exit(200,"上传成功",$path . '/' . $img);
        // return json(['code' => 0, 'path' => $prefix . '/' . $img, "showPath" => tostatic($prefix . '/' . $img)]);
    }

}