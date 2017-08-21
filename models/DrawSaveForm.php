<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

class DrawSaveForm extends Model
{
    const PASSWORD_MIN_LENGHT = 4;

    const IMAGE_MIME_TYPE = 'image/png';
    const IMAGE_WIDTH = 600;
    const IMAGE_HEIGHT = 300;

    public $canvas;
    public $password;
    public $password_repeat;
    public $body;

    /**
     * @var Image
     */
    private $Model;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['canvas', 'password'], 'required'],
            [['password', 'password_repeat'], 'string', 'min' => static::PASSWORD_MIN_LENGHT],
            [['canvas'], 'validateCanvas'],
            [['password'], 'validatePassword'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли должны совпадать!'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'canvas' => 'Изображение',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
        ];
    }

    public function validateCanvas()
    {
        if(!$this->validateCanvasByGd()){
            $this->addError('canvas', 'Файл не является изображением');
        }
    }

    private static function trimCanvasData($imgdata){
        return substr($imgdata, strpos($imgdata, ',')+1);
    }

    private function validateCanvasByFinfo()
    {
        if(!$this->canvas){
            return false;
        }

        $this->body = base64_decode(static::trimCanvasData($this->canvas));

        $f = finfo_open();
        $mime_type = finfo_buffer($f, $this->body, FILEINFO_MIME_TYPE);
        finfo_close($f);

        return $mime_type && $mime_type == static::IMAGE_MIME_TYPE;
    }

    private function validateCanvasByGd()
    {
        if(!$this->canvas){
            return false;
        }

        $info = getimagesizefromstring(base64_decode(static::trimCanvasData($this->canvas)));
        if (!$info) {
            return false;
        }

        return $info[0] === static::IMAGE_WIDTH
            && $info[1] === static::IMAGE_HEIGHT
            && $info['mime'] === static::IMAGE_MIME_TYPE;
    }

    private function mergeCanvas()
    {
        if(!$this->Model->body){
            return;
        }

        $src = imagecreatefromstring(base64_decode(static::trimCanvasData($this->Model->body)));
        $dest = imagecreatefromstring(base64_decode(static::trimCanvasData($this->canvas)));

        imagealphablending($dest, true);
        imagesavealpha($dest, true);

        imagecopy($dest, $src, 0, 0, 0, 0, static::IMAGE_WIDTH, static::IMAGE_HEIGHT);

        ob_start();
        imagepng($dest);
        $imageData = ob_get_contents();
        ob_end_clean();

        imagedestroy($src);
        imagedestroy($dest);

        $imageData = 'data:image/png;base64,'.base64_encode($imageData);
        $this->canvas = $imageData;
    }

    public function validatePassword()
    {
        if(!$this->Model->isNewRecord && !$this->Model->validatePassword($this->password)) {
            $this->addError('password', 'Пароль не подходит');
            return false;
        }
        return true;
    }

    public function save(Image $Model)
    {
        $this->mergeCanvas();

        $Model->body = ($this->canvas);

        if($Model->isNewRecord){
            $Model->salt = (Yii::$app->getSecurity()->generateRandomString(10));
            $Model->password = $Model::hashPassword($this->password, $Model->salt);
        }

        return $Model->save();
    }

    public function initFromImage(Image $Model)
    {
        $this->Model = $Model;
        $this->canvas = $Model->body;
    }
}