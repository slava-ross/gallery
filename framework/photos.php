<?php
/**
*   -D- Класс @photos - операции с фотографиями: добавление, удаление, получение списка;
*/
class photos
{
    /**
    *
    */
    const DEFAULT_IMAGE_DIRECTORY = 'images';
    const DEFAULT_THUMBS_DIRECTORY = 'thumbs';
    /**
     * -D - Локальный защищённый экземпляр объекта SimpleImage;
     * -V-  @simpleImage{simpleImage};
     */
    private $simpleImage = NULL;
    /**
     * -D, Method- Экземпляр объекта SimpleImage;
     *
     */
    public function addSimpleImage($simpleImage)
    {
        $this->simpleImage = $simpleImage;
    }
    /**
    *   -D- @addphoto - Метод выполняющий валидацию ввода полей описания товара и добавляющий информацию о товаре в файл;
    *
    */
    public function addPhoto($photoFileArray)
    {
        $errors = array();
        $messages = array();
        $extentions = array('jpeg','jpg','png', 'gif');
        $types = array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg');
        foreach ($photoFileArray["error"] as $key => $error)
        {
            if ($error != UPLOAD_ERR_OK)
            {
                $errors[] = "Не выбран файл или ошибка загрузки файла " . $photoFileArray["name"][$key] . "!";
            }
        }
        if (count($errors) == 0)
        {
            foreach ($photoFileArray["tmp_name"] as $key => $tmpName)
            {
                $fileName = basename($photoFileArray["name"][$key]);
                $fileSize = $photoFileArray["size"][$key];
                $fileType = $photoFileArray["type"][$key];
                $pathInfo = pathinfo($fileName);
                $fileExten = $pathInfo['extension'];
                if (!in_array($fileExten, $extentions))
                {
                    $errors[] = "Недопустимое расширение файла ($fileExten)! Файл: $fileName. Пожалуйста выберите JPEG, PNG или GIF";
                    continue;
                }
                $mimeType = mime_content_type($tmpName);
                if (!in_array($mimeType, $types))
                {
                    $errors[] = "Недопустимый тип файла ($mimeType)! Файл: $fileName. Допустимо загружать только изображения: image/gif, image/png, image/jpeg, image/pjpeg";
                    continue;
                }
                if ($fileSize > 16777216)
                {
                    $errors[] = "Недопустимый размер файла $fileName! Пожалуйста выберите файл объёмом не более 16 Мб";
                    continue;
                }
                $uploadsDir = self::DEFAULT_IMAGE_DIRECTORY;
                if (move_uploaded_file($tmpName, "$uploadsDir/$fileName"))
                {
                    $messages[] = "Файл $fileName добавлен в галерею";
                    $thumbsDir = self::DEFAULT_THUMBS_DIRECTORY;
                    
                    $this->simpleImage->load("$uploadsDir/$fileName");
                    if ($this->simpleImage->getWidth() < $this->simpleImage->getHeight())
                    {
                        $this->simpleImage->resizeToWidth(200);
                    }
                    else
                    {
                        $this->simpleImage->resizeToHeight(200);
                    }
                    $this->simpleImage->save("$thumbsDir/thumb_$fileName");
                }
                else
                {
                    $errors[] = "Ошибка сохранения файла $fileName!";
                }
            }
        }
        return [
            'messages'   => $messages,
            'errors'    => $errors,
        ];
    }
    /**
    *   -D- @getphotos - Метод для получения списка файлов фотографий в массиве;
    *
    */
    public function getPhotos()
    {
        $result = array();
        $errors = array();
        $dir = self::DEFAULT_IMAGE_DIRECTORY;
        $files = scandir($dir);
        foreach ($files as $file)
        {
            if ($file === '.' || $file === '..')
            {
                continue;
            }
            if (is_file($dir.DIRECTORY_SEPARATOR.$file))
            {
                $result[] = $file;
            }
        }
        return [
            'result'  => $result,
            'errors'  => $errors,
        ];
    }
}