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
            $errorMessages = array();
            $files = array();

            //$result = $photoSource->getPhotos();
            $dir = 'images/';
            $files = array_slice(scandir($dir), 2);
            print_r($files);

            $this->getTemplate( 'templates/header.tpl',
                array(
                    'title'=>'Фотоальбом',
                    'styles'=>'css/get_photos.css',
                )
            );
            $this->getTemplate( 'templates/get_photos.tpl',
                array(
                    'errorMessages' => $errorMessages,
                    'photoArray' => $files,
                    /*'photoArray' => $result['returnResult'],
                    'errorMessages' => $result['returnErrors'],*/
                )
            );
            $this->getTemplate( 'templates/footer.tpl' );
        }
        /**
         *  Метод сборки страницы добавления фотографии(й)
         *
         *
         */
        private function addPhotoPage() {
            include ('framework/photos.php');
            $photos = new photos;
            $result = array();

            $this->getTemplate(
                'templates/header.tpl',
                array(
                    'title'=>'Добавление фотографии',
                    'styles'=>'css/add_photo.css',
                )
            );

            if (isset($_POST['submit'])) {
                $result = $photos->addPhoto($_FILES['photo']);
                if ($result['success']) {

                    $this->getTemplate('templates/add_photo.tpl');
                } else {        // not success
                    $this->getTemplate('templates/add_photo.tpl');
                }
            } else {            // new form
                $this->getTemplate('templates/add_photo.tpl');
            }
            $this->getTemplate('templates/footer.tpl');
        }
        /**
         *  Метод сборки страницы отображения выбранной фотографии
         *
         *
         */
        private function showPhotoPage($photoFileName) {
            $this->getTemplate('templates/header.tpl',
                array(
                    'title'=>'Фотография: ' . $photoFileName,
                    'styles'=>'css/show_photo.css',
                )
            );
            $this->getTemplate('templates/show_photo.tpl',
                array(
                    'photoFileName' => $photoFileName,
                )
            );
            $this->getTemplate( 'templates/footer.tpl' );
        }
        /**
        *   -D- @router - Основной метод задающий "маршрут" приложения для генерации соответствующей страницы;
        *
        */
        public function router( $page ) {
            /**
            *   -D- Выбор метода для генерации нужной страницы;
            *
            */
            switch ($page) {
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