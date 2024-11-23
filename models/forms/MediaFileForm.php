<?php

namespace app\models\forms;

use app\models\MediaFile;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class MediaFileForm extends Model
{
    public UploadedFile $file;

    public int $postId;

    private string $UPLOAD_SUBDIR;

    public const UPLOAD_PATH = 'uploads';

    public const DIR_PATH_POST = 'post-images';

    private const IMAGE_RESOLUTION = 1024;

    private const IMAGE_JPG_QUALITY = 80;

    /**
     * @var bool Whether to skip the validation if the file is empty.
     */
    public bool $skipOnEmpty = false;

    /**
     * @var bool Whether to use the active name for the file.
     */
    public bool $useActiveName = false;

    public function __construct(string $subdir)
    {
        parent::__construct();
        $this->UPLOAD_SUBDIR = $subdir . DIRECTORY_SEPARATOR;
    }

    /**
     * @return array<mixed>
    */
    public function rules(): array
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => $this->skipOnEmpty, 'extensions' => 'png, jpg, jpeg, webp, gif', 'maxSize' => 5 * 1024 * 1024],
            [['postId', 'file'], 'required'],
            [['postId'], 'integer'],
        ];
    }

    private function getFilePathPrefix(): string
    {
        return date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR;
    }

    private function getFilePath(): string
    {
        return Yii::getAlias('@app') . DIRECTORY_SEPARATOR . self::UPLOAD_PATH . DIRECTORY_SEPARATOR . $this->UPLOAD_SUBDIR;
    }

    private function uploadConvertedImage(): MediaFile
    {
        if (!file_exists($this->getFilePath() . $this->getFilePathPrefix())) {
            mkdir($this->getFilePath() . $this->getFilePathPrefix(), 0777, true);
        }

        $filename = $this->getUniqueFilename();
        $path = $this->getFilePath() . $filename;
        $name = $this->file->name;

        $imagine = new Imagine();

        $size = new Box(self::IMAGE_RESOLUTION, self::IMAGE_RESOLUTION);
        $palette = new RGB();
        $targetImage = $imagine->create($size, $palette->color('#000', 100));

        $sourceImage = $imagine->open($this->file->tempName);

        $sourceSize = $sourceImage->getSize();
        $ratioWidth = self::IMAGE_RESOLUTION / $sourceSize->getWidth();
        $ratioHeight = self::IMAGE_RESOLUTION / $sourceSize->getHeight();
        $ratio = min($ratioWidth, $ratioHeight);

        $newWidth = (int)($sourceSize->getWidth() * $ratio);
        $newHeight = (int)($sourceSize->getHeight() * $ratio);

        $sourceImage->resize(new Box($newWidth, $newHeight));

        $posX = (int)((self::IMAGE_RESOLUTION - $newWidth) / 2);
        $posY = (int)((self::IMAGE_RESOLUTION - $newHeight) / 2);

        $targetImage->paste($sourceImage, new Point($posX, $posY));

        // Save the image
        $targetImage->save($path, [
        'jpeg_quality' => self::IMAGE_JPG_QUALITY
        ]);

        $fileModel = new MediaFile([
            'name' => $name,
            'path' => $this->UPLOAD_SUBDIR . $filename,
            'post_id' => $this->postId
        ]);
        if ($fileModel->save()) {
            return $fileModel;
        }
        unlink($path);
        throw new \Exception('Failed to save file model ' . print_r($fileModel->getErrors(), true));
    }

    private function getUniqueFilename(string $extension = 'jpg'): string
    {
        $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.' . $extension;
        $path = $this->getFilePath();
        while (file_exists($path . $filename)) {
            $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.' . $extension;
        }
        return $filename;
    }

    /**
     * @param array<mixed> $data
     * @param ?string $formName
     * @return bool
    */
    public function load($data, $formName = null): bool
    {
        if ($this->useActiveName) {
            $file = UploadedFile::getInstance($this, 'file');
        } else {
            $file = UploadedFile::getInstanceByName('file');
        }

        if ($file === null) {
            return false;
        }

        $this->file = $file;
        return true;
    }

    /**
     * Uploads the image and returns the path to the image.
     *
     * @return bool|MediaFile
     */
    public function upload(): bool|MediaFile
    {
        if ($this->validate()) {
            return $this->uploadConvertedImage();
        }
        return false;
    }
}
