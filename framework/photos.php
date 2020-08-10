<?php
    /**
    *   -D- Класс @photos - операции с фотографиями: добавление, удаление, получение списка;
    */
    class photos {
        /**
        */
        const DEFAULT_IMAGE_DIRECTORY = 'images/';
        /**
         * -D - Локальный защищённый экземпляр объекта SimpleImage;
         * -V-  @simpleImage{simpleImage};
         */
        private $simpleImage = NULL;
        /**
         * -D, Method- Экземпляр объекта SimpleImage;
         *
         */
        public function addSimpleImage($simpleImage) {
            $this->simpleImage = $simpleImage;
        }
        /**
        *   -D- @addphoto - Метод выполняющий валидацию ввода полей описания товара и добавляющий информацию о товаре в файл;
        *
        */
        public function addPhoto($photoFileArray) {

            $success = false;
            $errors = array();
            $extentions = array('jpeg','jpg','png', 'gif');
            $types = array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg');

            foreach ($photoFileArray["error"] as $key => $error) {
                if ($error != UPLOAD_ERR_OK) {
                    $errors[] = "Не выбран файл или ошибка загрузки файла!";
                }
            }
            if (count($errors) == 0) {
                $uploadsDir = self::DEFAULT_IMAGE_DIRECTORY;
                foreach ($photoFileArray["tmp_name"] as $key => $tmpName) {
                    //$fileName = $photoFileArray["name"][$key];
                    //$fileTmpName = $photoFileArray["tmp_name"][$key];
                    $fileName = basename($photoFileArray["name"][$key]);
                    $fileSize = $photoFileArray["size"][$key];
                    $fileType = $photoFileArray["type"][$key];
                    $pathInfo = pathinfo($fileName);
                    $fileExten = $pathInfo['extension'];
                    if (!in_array($fileExten, $extentions)) {
                        $errors[] = "Недопустимое расширение файла ($fileExten)! Пожалуйста выберите JPEG, PNG или GIF";
                        $success = false;
                        break;
                    }
                    $mimeType = mime_content_type($tmpName);
                    if (!in_array($mimeType, $types)) {
                        echo $mimeType;
                        $errors[] = "Недопустимый тип файла ($mimeType)! Допустимо загружать только изображения: image/gif, image/png, image/jpeg, image/pjpeg";
                        $success = false;
                        break;
                    }
                    if ($fileSize > 16777216) {
                        $errors[] = "Недопустимый размер файла! Пожалуйста выберите файл объёмом не более 16 Мб";
                        $success = false;
                        break;
                    }
                    if (move_uploaded_file($tmpName, "$uploadsDir/$fileName")) {
                        $success = true;
                    } else {
                        $errors[] = "Ошибка сохранения файла!";
                    }
                }
            }
            return [
                'success'   => $success,
                'errors'    => $errors,
            ];
        }
        /**
        *   -D- @getphotos - Метод для получения списка файлов фотографий в массиве;
        *
        */
        public function getPhotos() {
            $files = array();
            $errors = array();
            $dir = self::DEFAULT_IMAGE_DIRECTORY;
            $files = array_slice(scandir($dir), 2);
            return [
                'result'  => $files,
                'errors'  => $errors,
            ];
        }
    }
?>