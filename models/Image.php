<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property integer $image_id
 * @property string $password
 * @property string $salt
 * @property string $body
 * @property integer $created
 * @property integer $updated
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'salt'], 'required'],
            [['body'], 'safe'],
            [['created', 'updated'], 'integer'],
            [['password', 'salt'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'ID',
            'password' => 'Пароль',
            'salt' => 'Salt',
            'body' => 'Изображение',
            'created' => 'Добавлено',
            'updated' => 'Изменено',
        ];
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->created = time();
        } else {
            $this->updated = time();
        }
        return parent::beforeSave($insert);
    }

    static public function hashPassword($password, $salt){
        return Yii::$app->getSecurity()->generatePasswordHash($salt.$password.$salt);
    }

    public function validatePassword($password){
        return $password && $this->password
            && (Yii::$app->getSecurity()->validatePassword($this->salt.$password.$this->salt, $this->password));
    }

    public function getUrl(){
        return $this->body;
    }
}
