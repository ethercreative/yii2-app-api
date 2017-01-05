<?php

namespace app\traits;

use Yii;
use yii\helpers\ArrayHelper;

trait Image
{
	private $_fileArray = [];

	public function resize($save = false)
	{
		$folder = $this->formattedStructure;

		if (!is_dir($folder))
		{
			mkdir($folder, 0777, true);
			chmod($folder, 0777);
		}

		$this->_fileArray = [
			'name' => $this->file->name,
			'filename' => join('.', [microtime(true), rand(10000, 99999), $this->file->extension]),
			'type' => $this->file->type,
			'size' => $this->file->size,
			'extension' => $this->file->extension,
		];

		$this->file->saveAs($folder . '/' . $this->_fileArray['filename']);

		foreach ($this->sizes as $key => $size)
		{
			$filename = $this->generateFilenameSize($key);

			if (!file_exists($folder . '/' . $filename))
			{
				$imagick = new \Imagick;
				$imagick->readImage($folder . '/' . ArrayHelper::getValue($this->_fileArray, 'filename'));
				$imagick->cropThumbnailImage($size[0], $size[1]);
				$imagick->writeImage($folder . '/' . $filename);
			}
		}

		return true;
	}

	public function getFormattedStructure($path = true, $id = true)
	{
		$structure = strtr(trim($this->structure, '/'), [
			'{id}' => $id ? $this->id : null,
		]);

		return ($path ? Yii::$app->basePath . '/web' : '') . '/uploads/' . rtrim($structure, '/');
	}

	public function generateFilenameSize($key)
	{
		$filename = ArrayHelper::getValue($this->_fileArray, 'filename');
		$extension = ArrayHelper::getValue($this->_fileArray, 'extension', 'jpg') ?: 'jpg';

		return str_replace('.' . $extension, '-' . join('x', $this->sizes[$key]) . '.' . $extension, $filename);
	}

	public function getFileArray()
	{
		return $this->_fileArray;
	}

	public function getUrl()
	{
		return $this->getFormattedStructure(false) . '/' . ArrayHelper::getValue($this->_fileArray, 'filename');
	}

	public function getUrl_sizes()
	{
		$urls = [];

		foreach ($this->sizes as $key => $size)
		{
			$_key = !is_numeric($key) ? $key : join('x', $size);
			$urls[$_key] = $this->getFormattedStructure(false) . '/' . $this->generateFilenameSize($key);
		}

		return $urls;
	}
}
