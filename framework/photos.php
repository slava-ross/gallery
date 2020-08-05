<?php
    /**
    *   -D- Класс @photos - операции с фотографиями: добавление, удаление, получение списка;
    */
    class photos {
        /**
        *   -V- @error{ array }: массив сообщений об ошибках, передаваемых в шаблон для отображения;
        *   -V- @fileName{ string }: путь и имя файла с информацией о товарах в формате JSON;
        *   -V- @photosArr{ array }: массив с информацией о товарах, получаемой из файлов и передаваемый для дальнейших операций в другие объекты/методы приложения;
        *   -V- @returnArray{ array }: массив с возвращаемой методами класса информацией, который содержит как рабочую информацию, так и сообщения об ошибках;
        *   -V- @writeResult{ integer/boolean }: результат записи в файл: false - в случае возникновения ошибок, кол-во байт записанной информации - при успешном завершении;
        */
        private $errors = array();
        private $fileName = 'files/photos.json';
        private $photosArr = array();
        private $returnArray = array();
        private $writeResult = NULL;
        /**
        *   -D- @addphoto - Метод выполняющий валидацию ввода полей описания товара и добавляющий информацию о товаре в файл;
        *
        */
        public function addPhoto ( $photoName, $photoDescr, $photoAuthor, $photoDate ) {
            if( empty( $photoName ) ) {
                $this->errors[] = "Укажите заголовок!";
            }
            if( empty( $photoDescr ) ) {
                $this->errors[] = "Укажите описание!";
            }
            if( empty( $photoAuthor ) ) {
                $this->errors[] = "Укажите автора!";
            }
            if( empty( $photoDate ) ) {
                $this->errors[] = "Укажите дату создания!";
            }
            
            if( count( $this->errors )==0 ) {
                $photo = array(
                    "photoName" => $photoName,
                    "photoDescr" => $photoDescr,
                    "photoAuthor" => $photoAuthor,
                    "photoDate" => $photoDate
                );
                $json = file_get_contents( $this->fileName );
                if ( !empty( $json ) ) {
                    $this->photosArr = json_decode( $json, true );
                }
                $this->photosArr[] = $photo;
                $this->writeResult = file_put_contents( $this->fileName, json_encode( $this->photosArr ) );
                if ( $this->writeResult === false ) {
                    $this->errors[] = "Ошибка записи в файл!";
                }
            }
            $this->returnArray['returnErrors'] = $this->errors;
            return $this->returnArray;
        }
        /**
        *   -D- @delphoto - Метод выполняющий удаление выбранного товара из файла;
        *
        */
        public function delphoto ( $photoIndex ) {
            $json = file_get_contents( $this->fileName );
            $this->photosArr = json_decode( $json, true );
            array_splice( $this->photosArr, $photoIndex, 1 );
            $this->writeResult = file_put_contents( $this->fileName, json_encode( $this->photosArr ) );
            if ( $this->writeResult === false ) {
                $this->errors[] = "Ошибка записи в файл!";
            }
            $this->returnArray['returnErrors'] = $this->errors;
            $this->returnArray['returnResult'] = $this->photosArr;
            return $this->returnArray;
        }
        /**
        *   -D- @getphotos - Метод для получения списка товаров из файла в массив;
        *
        */
        public function getphotos () {
            $json = file_get_contents( $this->fileName );
            if ( $json === false ) {
                $this->errors[] = "Ошибка чтения файла!";
            }
            else {
                $this->photosArr = json_decode( $json, true );   
            }
            $this->returnArray['returnErrors'] = $this->errors;
            $this->returnArray['returnResult'] = $this->photosArr;
            return $this->returnArray;
        }
    }
?>



<?php
    /**
    *   -D- Класс @items - работа с товарами;
    */
    class items {
        /**
         * -D - Локальный защищённый экземпляр объекта БД;
         * -V- {db} @db: БД;
         */
        private $db = NULL;
        /**
         * -D, Method- Метод выполняющий валидацию ввода полей информации о товаре и добавляющий её в Б/Д;
         * -V- {String} ;
         * -R- Array(
            ''  => ()//
            ''  => (bool),      // true - успешное подключение, false - есть ошибки;
            ''  => array(),     // массив ошибок в строчном виде;
         );
         */
        public function addItem ( $title, $description, $dateCreated, $cost, $amount, $fileArray ) {
            $success = false;
            $id = NULL;
            $errors = array();

            $title = trim( $title );
            $dateCreated = trim( $dateCreated );
            $cost = trim( $cost );
            $amount = trim( $amount );

            if( empty( $title )) {
                $errors[] = "Укажите наименование товара!";
            } elseif ( mb_strlen( $title, 'utf-8' ) > 50 ) {
                $errors[] = "Наименование должно быть не более 50 символов!";
            }

            if( empty( $dateCreated )) {
                $errors[] = "Укажите дату поступления товара!";
            } elseif ( !preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/ui', $dateCreated )) {
                $errors[] = "Дата должна быть в формате: 00.00.0000";
            } else {
                $dateCreated = date('Y-m-d H:i:s', strtotime( $dateCreated ));
            }

            if( empty( $cost )) {
                $errors[] = "Укажите стоимость товара!";
            } elseif ( !preg_match( '/\d{1,13}[,\.]?(\d{1,2})?/u', $cost )) {
                $errors[] = "Стоимость должна быть в формате: 0000; 0000.00; 0000,00!";
            }

            if( empty( $amount )) {
                $errors[] = "Укажите количество товара!";
            }

            foreach ( $fileArray["error"] as $key => $error ) {
                if ( $error != UPLOAD_ERR_OK ) {
                $errors[] = "Не выбран файл или ошибка загрузки файла!";
                }
            }

            if( count( $errors ) == 0 ) {
                $query = '
                    INSERT INTO
                        items (
                            `title`,
                            `description`,
                            `date-created`,
                            `cost`,
                            `amount`
                        )
                    VALUES (
                        "'.$this->db->realEscape( $title ).'",
                        "'.$this->db->realEscape( $description ).'",
                        "'.$dateCreated.'","'.$this->db->realEscape( $cost ).'",
                        '.$amount.'
                    );
                ';
                $res = $this->db->query($query, 'insert');
                if ( $res['success'] ) {
                    $success = true;
                    $id = $res['id'];

                    $uploadsDir = "uploads";
                    foreach ( $fileArray["tmp_name"] as $key => $tmpName ) {
                        $fileName = basename( $fileArray["name"][$key] );
                        $path_info = pathinfo( $fileName );
                        $fileExten = $path_info['extension'];
                        $newFileName = uniqid('photo_').'.'.$fileExten;
                        // print( 'TMP: '.$tmpName.' NAME: '.$fileName.' EXT: '.$fileExten.' NEW: '.$newFileName);
                        move_uploaded_file( $tmpName, "$uploadsDir/$newFileName" );
                        $query = '
                            INSERT INTO
                                files (
                                    `item-id`,
                                    `name`,
                                    `orig-name`
                                )
                            VALUES (
                                "'.$id.'",
                                "'.$this->db->realEscape( $newFileName ).'",
                                "'.$this->db->realEscape( $fileName ).'"
                            );
                        ';
                        $res = $this->db->query($query, 'insert');
                    }
                } else {
                    $errors = $res['errors'];
                }
            }
            return array(
                'success'   => $success,
                'errors'    => $errors,
                'id'        => $id
            );
        }
    }
?>

