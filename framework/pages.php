<?php
    /**
    *   -D- @pages - Класс "сборщика страниц" (Page Controller);
    *
    */
    class pages {
        /**
        *   -D- @getTemplate - Метод подключения шаблона с передачей ему необходимых для отображения страницы параметров;
        */
        public function getTemplate( $file, $vars=array() ) {
            include( $file );
        }

        private function getPhotosPage() {
            /**
            *   -V- @photoSource{ photos }: экземпляр объекта, работающего с фотографиями (отображение списка, добавление);
            *   -V- @result{ array }: массив с результатами работы методов объекта, содержащий как рабочую информацию, так и сообщения об ошибках;
            */
            include ('framework/photos.php');
            $photoSource = new photos;
            $result = array();
/*
            if ( isset( $_GET['item_id'] )) {
                $result = $itemsSource->delItem( $_GET['item_id'] );
            }
            else {
                $result = $itemsSource->getItems();
            }*/
            $this->getTemplate( 'templates/header.tpl',
                array(
                    'title'=>'Фотоальбом',
                    'styles'=>'css/get_photos.css',
                )
            );
            $this->getTemplate( 'templates/get_photos.tpl',
                array(
                    'photoArray' => $result['returnResult'],
                    'errorMessages' => $result['returnErrors'],
                )
            );
            $this->getTemplate( 'templates/footer.tpl' );
        }

        private function addPhotoPage() {
            include ('framework/photos.php');
            $photoSource = new photos;
            $result = array();

            $this->getTemplate( 'templates/header.tpl',
                array(
                    'title'=>'Добавление фотографии',
                    'styles'=>'css/add_photo.css',
                )
            );

            if (isset($_POST['submit'])) {
                   /* $result = $photo->addPhoto($_FILES['photo']);
                    if ($result['success']) {

                        $messages[] = "Фотография добавлена";

                        $this->getTemplate(
                            'templates/header.tpl',
                            array(
                                'title'=>'Фотоальбом',
                                'styles'=>'css/get_photos.css',
                            )
                        );
                        $this->getTemplate(
                            'templates/get_photos.tpl',
                            array(
                                'messages' => $messages
                            )
                        );
                    } else { // not success
                        $this->getTemplate(
                            'templates/admin/header.tpl',
                            array(
                                'title'=>'Добавление товара',
                                'styles'=>'css/add_item.css',
                            )
                        );
                        $this->getTemplate(
                            'templates/add_photo.tpl',
                            array(
                                'errors' => $result['errors']
                            )
                        );
                    }*/
                } else { // new form
                    $this->getTemplate(
                        'templates/header.tpl',
                        array(
                            'title'=>'Добавление фотографии',
                            'styles'=>'css/add_photo.css',
                        )
                    );
                    $this->getTemplate( 'templates/add_photo.tpl' );
                }

                $this->getTemplate( 'templates/footer.tpl' );
        }


/*
                if (isset( $_POST['submit'])) {
                    if ( !isset( $_POST['gender'] )) $_POST['gender'] = '';
                    if ( !isset( $_POST['city'] )) $_POST['city'] = '';
                    $result = $itemsSource->addItem( $_POST['item_name'], $_POST['item_descr'], $_POST['item_author'], $_POST['item_date'] );

                    $this->getTemplate( 'templates/add_item.tpl',
                        array(
                            'is_send' => true,
                            'errorMessages' => $result['returnErrors']
                        )
                    );
                }
                else {
                    $this->getTemplate( 'templates/add_item.tpl', array( 'is_send' => false ));
                }
                $this->getTemplate( 'templates/footer.tpl' );
        }
*/
        private function showPhotoPage($photoFileName) {
            $this->getTemplate( 'templates/header.tpl',
                array(
                    'title'=>$photoFileName,
                    'styles'=>'css/show_photo.css',
                )
            );
            $this->getTemplate( 'templates/show_photo.tpl',
                array(
                    'photoFileName' => $photoFileName,
                )
            );
            $this->getTemplate( 'templates/footer.tpl' );
        }
        /**
        *   -D- @router - Основной метод задающий "маршрут" приложения для генерации соответствующей страницы;
        */
        public function router( $page ) {
            /**
            *   -D- Ввыбор "пути" для генерации нужной страницы;
            */
            switch ($page) {
                /**
                *   -D- Ветвь создания и отображения страницы "Фотоальбом";
                *   -D- Здесь же происходит отработка удаления фотографии из списка и последующее его отображение;
                */
                case 'add_photo':
                    $this->addPhotoPage();
                    break;
                case 'show_photo':
                    $this->showPhotoPage($_GET['imageName']);
                    break;
                case 'get_photos':
                default:
                    $this->getPhotosPage();
            }
        }
    }
?>