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
        *   -D- @addphoto - Метод выполняющий валидацию ввода полей описания товара и добавляющий информацию о товаре в файл;
        *
        */
        public function addPhoto ($photoFileArray) {
            
            var_dump($photoFileArray);

            $types = array('image/gif', 'image/png', 'image/jpeg', 'image/pjpeg');
            
            /*if (!in_array($_FILES['file']['type'], $types)){
                 echo 'Недопустимый тип файла. Допустимо загружать только изображения: *.gif, *.png, *.jpg';
            }*/

        
            $success = false;
            $errors = array();

            foreach ( $photoFileArray["error"] as $key => $error ) {
                if ( $error != UPLOAD_ERR_OK ) {
                $errors[] = "Не выбран файл или ошибка загрузки файла!";
                }
            }

            if (count($errors) == 0) {
                $uploadsDir = './images';
                foreach ($photoFileArray["tmp_name"] as $key => $tmpName) {
                    $fileName = basename($photoFileArray["name"][$key]);
                    $path_info = pathinfo($fileName);
                    $fileExten = $path_info['extension'];
                    $newFileName = uniqid('photo_').'.'.$fileExten;
                    echo "<br>";
                    print('TMP: '.$tmpName.' -=- NAME: '.$fileName.' -=- EXT: '.$fileExten.' -=- NEW: '.$newFileName);
                    echo "<br>";
                    move_uploaded_file($tmpName, "$uploadsDir/$newFileName");
                    $success = true;
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