<?php

namespace trntv\filekit\storage\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "file_storage_item".
 *
 * @property integer $id
 * @property string $repository
 * @property string $category
 * @property string $url
 * @property string $path
 * @property integer $size
 * @property string $mimeType
 * @property integer $upload_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class FileStorageItem extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_UPLOADED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file_storage_item}}';
    }

    public function scenarios()
    {
        return [
            'default'=>['url', 'path', 'size', 'mimeType', 'upload_ip']
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repository', 'path', 'size', 'created_at'], 'required'],
            [['url'], 'unique'],
            [['size'], 'integer'],
            [['repository'], 'string', 'max' => 32],
            [['url', 'path'], 'string', 'max' => 2048],
            [['category'], 'string', 'max' => 128],
            [['mimeType'], 'string', 'max' => 64],
            [['status'], 'default', 'value' => self::STATUS_UPLOADED],
            [['status'], 'in', 'range' => [self::STATUS_UPLOADED, self::STATUS_DELETED]],
            [['upload_ip'], 'default', 'value' => function(){
                return Yii::$app->request->isConsoleRequest
                    ? null
                    : Yii::$app->request->userIP;
            }],
            [['upload_ip'], 'string', 'max' => 15],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function behaviors(){
        return [
          TimestampBehavior::className()
      ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'repository' => Yii::t('app', 'Repository'),
            'category' => Yii::t('app', 'Category'),
            'url' => Yii::t('app', 'Url'),
            'path' => Yii::t('app', 'Path'),
            'size' => Yii::t('app', 'Size'),
            'mime' => Yii::t('app', 'Mime Type'),
            'upload_ip' => Yii::t('app', 'Upload IP Adress'),
            'stauts' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Upload Time'),
        ];
    }
}
