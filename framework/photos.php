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
            $extentions = array('jpeg','jpg','png');

            //$types = array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg');

            /*if (!in_array($_FILES['file']['type'], $types)){
                 echo 'Недопустимый тип файла. Допустимо загружать только изображения: *.gif, *.png, *.jpg';
            }*/
// ##################################################
/*            if (isset($_FILES['photo'])) {
                $errors = array();
                $file_name = $_FILES['photo']['name'];
                $file_size = $_FILES['photo']['size'];
                $file_tmp = $_FILES['photo']['tmp_name'];
                $file_type = $_FILES['photo']['type'];
                $file_ext = strtolower(end(explode('.',$_FILES['photo']['name'])));
                echo "<br>";
                print('TMP: '.$file_tmp.' ### NAME: '.$file_name.' ### EXT: '.$file_ext.' ### SIZE: '.$file_size.' ### TYPE: '.$file_type);
                echo "<br>";

                $extentions = array('jpeg','jpg','png');

                if (!in_array($file_ext, $extentions)) {
                    $errors[] = "Недопустимое расширение файла! Пожалуйста выберите JPEG или PNG";
                }

                if ($file_size > 16777216) {
                    $errors[] = "Недопустимый размер файла! Пожалуйста выберите файл объёмом не более 16 Мб";
                }

                if (count($errors) == 0) {
                    if (move_uploaded_file($file_tmp, "images/".$file_name)) {
                        $success = true;
                    } else {
                       $errors[] = "Ошибка сохранения файла!";
                    }
                }
            }*/
// ##################################################



            foreach ($photoFileArray["error"] as $key => $error) {
                if ($error != UPLOAD_ERR_OK) {
                    $errors[] = "Не выбран файл или ошибка загрузки файла!";
                }
            }

            if (count($errors) == 0) {
                $uploadsDir = 'images';
                echo "<br>";
                var_dump($photoFileArray);
                echo "<br>";
                foreach ($photoFileArray["tmp_name"] as $key => $tmpName) {
                    $fileName = basename($photoFileArray["name"][$key]);
                    $pathInfo = pathinfo($fileName);
                    $fileExten = $pathInfo['extension'];
                    if (!in_array($fileExten, $extentions)) {
                        $errors[] = "Недопустимое расширение файла ($fileExten)! Пожалуйста выберите JPEG или PNG";
                    }
                    $newFileName = uniqid('photo_').'.'.$fileExten;
                    echo "<br>";
                    print_r('TMP: '.$tmpName.' -=- NAME: '.$fileName.' -=- EXT: '.$fileExten.' -=- NEW: '.$newFileName.' -=- PATH: '.$pathInfo);
                    echo "<br>";
                    if (move_uploaded_file($tmpName, "$uploadsDir/$newFileName")) {
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


            $this->returnArray['returnErrors'] = $this->errors;
            $this->returnArray['returnResult'] = $this->photosArr;
            return $this->returnArray;
        }
    }
?>