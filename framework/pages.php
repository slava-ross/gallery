<?php
/**
*   -D- @pages - Класс "сборщика страниц" (Page Controller);
*/
class pages
{
    /**
    *   -D- @getTemplate - Метод подключения шаблона с передачей ему необходимых для отображения страницы параметров;
    */
    public function getTemplate($file, $vars=array())
    {
        include($file);
    }

    private function getPhotosPage()
    {
        /**
        *   -V- @photoSource{ photos }: экземпляр объекта, работающего с фотографиями (отображение списка, добавление);
        *   -V- @result{ array }: массив с результатами работы методов объекта, содержащий как рабочую информацию, так и сообщения об ошибках;
        */
        include ('framework/photos.php');
        $photoSource = new photos;
        $result = $photoSource->getPhotos();

        $this->getTemplate('templates/header.tpl',
            array(
                'title'=>'Фотоальбом',
                'styles'=>'css/get_photos.css',
            )
        );
        $this->getTemplate('templates/get_photos.tpl',
            array(
                'photoArray' => $result['result'],
                'errorMessages' => $result['errors'],
            )
        );
        $this->getTemplate('templates/footer.tpl');
    }
    /**
     *  Метод сборки страницы добавления фотографии(й)
     */
    private function addPhotoPage()
    {
        include ('framework/simple_image.php');
        $simpleImage = new SimpleImage;
        include ('framework/photos.php');
        $photos = new photos;
        $photos->addSimpleImage($simpleImage);
        $result = array();
        $this->getTemplate(
            'templates/header.tpl',
            array(
                'title'=>'Добавление фотографии',
                'styles'=>'css/add_photo.css',
            )
        );
        if (isset( $_POST['submit']))
        {
            $result = $photos->addPhoto($_FILES['photo']);
            $this->getTemplate(
                'templates/add_photo.tpl',
                array(
                    'messages' => $result['messages'],
                    'errors' => $result['errors'],
                )
            );
        }
        else
        {
            $this->getTemplate('templates/add_photo.tpl');
        }
        $this->getTemplate('templates/footer.tpl');
    }
    /**
     *  Метод сборки страницы отображения выбранной фотографии
     */
    private function showPhotoPage($imageFileName)
    {
        $this->getTemplate('templates/header.tpl',
            array(
                'title'=>'Фотография: ' . $imageFileName,
                'styles'=>'css/show_photo.css',
            )
        );
        $this->getTemplate('templates/show_photo.tpl',
            array(
                'imageFileName' => $imageFileName,
            )
        );
        $this->getTemplate('templates/footer.tpl');
    }
    /**
    *   -D- @router - Основной метод задающий "маршрут" приложения для генерации соответствующей страницы;
    */
    public function router($page)
    {
        /**
        *   -D- Выбор метода для генерации нужной страницы;
        */
        switch ($page)
        {
            case 'add_photo':
                $this->addPhotoPage();
                break;
            case 'show_photo':
                $this->showPhotoPage($_GET['image_name']);
                break;
            case 'get_photos':
            default:
                $this->getPhotosPage();
        }
    }
}