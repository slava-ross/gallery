<?php
    /**
    *   -D- Класс @photos - операции с фотографиями: добавление, удаление, получение списка;
    */
    class photos {
        /**
        *   -V- @error{ array }: массив сообщений об ошибках, передаваемых в шаблон для отображения;
        *   -V- @photosArr{ array }: массив с информацией об изображениях, получаемой из файлов и передаваемый для дальнейших операций в другие объекты/методы приложения;
        *   -V- @returnArray{ array }: массив с возвращаемой методами класса информацией, который содержит как рабочую информацию, так и сообщения об ошибках;
        *   -V- @writeResult{ integer/boolean }: результат записи в файл: false - в случае возникновения ошибок, кол-во байт записанной информации - при успешном завершении;
        */
        const DEFAULT_IMAGE_DIRECTORY = 'images/';
        private $errors = array();
        private $photosArr = array();
        private $returnArray = array();
        private $writeResult = NULL;

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
                    if (!in_array($fileType, $types)) {
                        $errors[] = "Недопустимый тип файла ($fileType)! Допустимо загружать только изображения: image/gif, image/png, image/jpeg, image/pjpeg";
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